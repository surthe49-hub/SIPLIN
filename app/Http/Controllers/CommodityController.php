<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use App\Models\Commodity;
use App\Models\CommodityImage;
use App\Models\Location;
use App\Models\User;
use App\Notifications\CommodityCreated;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CommodityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:commodities.view', only: ['index', 'show']),
            new Middleware('permission:commodities.create', only: ['create', 'store']),
            new Middleware('permission:commodities.edit', only: ['edit', 'update']),
            new Middleware('permission:commodities.delete', only: ['destroy']),
        ];
    }

    /**
     * Tampilkan daftar barang.
     */
    public function index(Request $request): View
    {
        $query = Commodity::with(['category', 'location', 'primaryImage']);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('item_code', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%")
                    ->orWhere('brand', 'like', "%{$search}%")
                    ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by location
        if ($request->filled('location_id')) {
            $query->where('location_id', $request->location_id);
        }

        // Filter by condition
        if ($request->filled('condition')) {
            $query->where('condition', $request->condition);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        $perPage = min($request->get('per_page', 15), 100);
        $commodities = $query->paginate($perPage)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('commodities.index', compact('commodities', 'categories', 'locations'));
    }

    /**
     * Tampilkan form tambah barang.
     */
    public function create(): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();

        return view('commodities.create', compact('categories', 'locations'));
    }

    /**
     * Simpan barang baru.
     * NOTE: Field acquisition_type, acquisition_source, purchase_price, responsible_person
     * tidak ditampilkan di UI (disederhanakan untuk PLN ULP Cilacap).
     * Default value di-set otomatis di controller.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'item_code' => ['nullable', 'string', 'max:50', 'unique:commodities,item_code'],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', function ($attribute, $value, $fail) {
                if ($value !== 'custom' && !\App\Models\Location::where('id', $value)->exists()) {
                    $fail('Lokasi yang dipilih tidak valid.');
                }
            }],
            'custom_location' => ['required_if:location_id,custom', 'nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            // Field-field di bawah dibuat nullable - tidak ditampilkan di form lagi
            'acquisition_type' => ['nullable', 'in:pembelian,hibah,bantuan,produksi,lainnya'],
            'acquisition_source' => ['nullable', 'string', 'max:255'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            // Field yang masih ditampilkan
            'quantity' => ['required', 'integer', 'min:1'],
            'condition' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'purchase_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'specifications' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'max:2048'],
        ]);

        $validated['created_by'] = Auth::id();
        // Set default untuk field yang sudah tidak ditampilkan di UI
        $validated['acquisition_type'] = $validated['acquisition_type'] ?? 'pembelian';
        $validated['purchase_price'] = $validated['purchase_price'] ?? 0;

        // Handle custom location
        if ($validated['location_id'] === 'custom') {
            $location = \App\Models\Location::create([
                'name' => $validated['custom_location'],
                'code' => 'LOC-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'building' => 'Manual Input',
                'floor' => null,
                'room' => null,
            ]);
            $validated['location_id'] = $location->id;
        }

        unset($validated['custom_location']);

        try {
            $commodity = Commodity::create($validated);

            // Handle images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $path = $image->store('commodities', 'public');
                    $commodity->images()->create([
                        'image_path' => $path,
                        'original_name' => $image->getClientOriginalName(),
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            // Send notification to all admin users
            $adminUsers = User::where('role', 'admin')->get();
            Notification::send($adminUsers, new CommodityCreated($commodity, Auth::user()));

            return redirect()->route('commodities.show', $commodity)
                ->with('success', 'Barang berhasil ditambahkan.');
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                return back()->with('error', 'Kode barang sudah digunakan. Sistem akan generate kode baru otomatis.')
                    ->withInput(['item_code' => '']);
            }

            return back()->with('error', 'Terjadi kesalahan database. Silakan coba lagi.')->withInput();
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan barang. Silakan coba lagi.')->withInput();
        }
    }

    /**
     * Tampilkan detail barang.
     */
    public function show(Commodity $commodity): View
    {
        $commodity->load([
            'category',
            'location',
            'images',
            'creator',
            'updater',
            'transfers.fromLocation',
            'transfers.toLocation',
            'maintenances',
            'disposals',
        ]);

        return view('commodities.show', compact('commodity'));
    }

    /**
     * Tampilkan form edit barang.
     */
    public function edit(Commodity $commodity): View
    {
        $categories = Category::active()->orderBy('name')->get();
        $locations = Location::active()->orderBy('name')->get();
        $commodity->load('images');

        return view('commodities.edit', compact('commodity', 'categories', 'locations'));
    }

    /**
     * Update barang.
     * Field yang tidak di form (acquisition_type, purchase_price, dll) tidak akan
     * di-overwrite - nilai existing dipertahankan.
     */
    public function update(Request $request, Commodity $commodity): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'item_code' => ['nullable', 'string', 'max:50', 'unique:commodities,item_code,' . $commodity->id],
            'category_id' => ['required', 'exists:categories,id'],
            'location_id' => ['required', function ($attribute, $value, $fail) {
                if ($value !== 'custom' && !\App\Models\Location::where('id', $value)->exists()) {
                    $fail('Lokasi yang dipilih tidak valid.');
                }
            }],
            'custom_location' => ['required_if:location_id,custom', 'nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'model' => ['nullable', 'string', 'max:255'],
            'serial_number' => ['nullable', 'string', 'max:255'],
            // Field-field nullable - kalau tidak dikirim form, nilai existing dipertahankan
            'acquisition_type' => ['nullable', 'in:pembelian,hibah,bantuan,produksi,lainnya'],
            'acquisition_source' => ['nullable', 'string', 'max:255'],
            'purchase_price' => ['nullable', 'numeric', 'min:0'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            // Field yang masih ditampilkan
            'quantity' => ['required', 'integer', 'min:1'],
            'condition' => ['required', 'in:baik,rusak_ringan,rusak_berat'],
            'purchase_year' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'specifications' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'images.*' => ['nullable', 'image', 'max:2048'],
            'delete_images' => ['nullable', 'array'],
            'delete_images.*' => ['exists:commodity_images,id'],
            'primary_image' => ['nullable', 'exists:commodity_images,id'],
        ]);

        $validated['updated_by'] = Auth::id();

        // Untuk field yang TIDAK ada di form edit, jangan overwrite nilai existing.
        // Kalau request tidak kirim field tersebut, hapus dari $validated supaya
        // Eloquent update() tidak menyentuh kolom itu di DB.
        foreach (['acquisition_type', 'acquisition_source', 'purchase_price', 'responsible_person'] as $hiddenField) {
            if (!$request->has($hiddenField)) {
                unset($validated[$hiddenField]);
            }
        }

        // Handle custom location
        if ($validated['location_id'] === 'custom') {
            $location = \App\Models\Location::create([
                'name' => $validated['custom_location'],
                'code' => 'LOC-' . strtoupper(\Illuminate\Support\Str::random(6)),
                'building' => 'Manual Input',
                'floor' => null,
                'room' => null,
            ]);
            $validated['location_id'] = $location->id;
        }

        unset($validated['custom_location']);

        // Delete selected images
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = CommodityImage::find($imageId);
                if ($image && $image->commodity_id === $commodity->id) {
                    Storage::disk('public')->delete($image->image_path);
                    $image->delete();
                }
            }
        }

        // Handle new images
        if ($request->hasFile('images')) {
            $lastOrder = $commodity->images()->max('sort_order') ?? -1;
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('commodities', 'public');
                $commodity->images()->create([
                    'image_path' => $path,
                    'original_name' => $image->getClientOriginalName(),
                    'is_primary' => false,
                    'sort_order' => $lastOrder + $index + 1,
                ]);
            }
        }

        // Set primary image
        if ($request->filled('primary_image')) {
            $commodity->images()->update(['is_primary' => false]);
            $commodity->images()->where('id', $request->primary_image)->update(['is_primary' => true]);
        }

        unset($validated['images'], $validated['delete_images'], $validated['primary_image']);
        $commodity->update($validated);

        return redirect()->route('commodities.show', $commodity)
            ->with('success', 'Barang berhasil diperbarui.');
    }

    /**
     * Hapus barang.
     */
    public function destroy(Commodity $commodity): RedirectResponse
    {
        if ($commodity->transfers()->whereIn('status', ['pending', 'approved'])->exists()) {
            return back()->with('error', 'Barang tidak bisa dihapus karena masih memiliki transfer yang belum selesai.');
        }

        $commodity->delete();

        return redirect()->route('commodities.index')
            ->with('success', 'Barang berhasil dihapus.');
    }

    /**
     * Preview kode barang berdasarkan kategori (API).
     */
    public function previewCode(Request $request)
    {
        $categoryId = $request->input('category_id');
        $code = Commodity::previewItemCode($categoryId ? (int) $categoryId : null);

        return response()->json([
            'code' => $code,
            'category_id' => $categoryId,
        ]);
    }

    /**
     * Export daftar barang ke PDF.
     */
    public function export(Request $request)
    {
        $commodities = Commodity::withRelations()->orderBy('name')->get();

        $pdf = Pdf::loadView('reports.pdf.inventory', [
            'commodities' => $commodities,
            'title' => 'Daftar Inventaris Barang',
            'date' => now()->format('d F Y'),
            'filters' => []
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->download('inventaris-barang-' . now()->format('Y-m-d') . '.pdf');
    }
}