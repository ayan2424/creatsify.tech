<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\ServicePackage;
use Core\Request;
use Core\Response;

/**
 * Creatsify Digital Agency - Admin Service Packages Manager
 */
class PackagesController
{
    public function index(Request $request): Response
    {
        $trackFilter = trim((string) $request->input('track', 'all'));
        $allPackages = ServicePackage::all();
        $tracks      = ServicePackage::tracks();

        $packages = $allPackages;
        if ($trackFilter !== 'all' && isset($tracks[$trackFilter])) {
            $packages = array_values(array_filter($allPackages, static fn(array $p) => ($p['track'] ?? '') === $trackFilter));
        }

        return view('admin/packages/index', [
            'packages'    => $packages,
            'allPackages' => $allPackages,
            'tracks'      => $tracks,
            'trackFilter' => $trackFilter,
            'meta_title'  => 'Service Packages & Pricing | Creatsify Admin',
        ]);
    }

    public function create(Request $request): Response
    {
        return view('admin/packages/form', [
            'package'    => null,
            'tracks'     => ServicePackage::tracks(),
            'meta_title' => 'Create Service Package | Creatsify Admin',
        ]);
    }

    public function store(Request $request): Response
    {
        $title = trim((string) $request->input('title', ''));
        if ($title === '') {
            flash('error', 'Package title is required.');
            return Response::redirect('/admin/packages/create');
        }

        $id = ServicePackage::create([
            'title'         => $title,
            'slug'          => trim((string) $request->input('slug', '')),
            'track'         => trim((string) $request->input('track', 'dev')),
            'tier_badge'    => trim((string) $request->input('tier_badge', 'Package')),
            'subtitle'      => trim((string) $request->input('subtitle', '')),
            'price_label'   => trim((string) $request->input('price_label', 'Custom')),
            'billing_cycle' => trim((string) $request->input('billing_cycle', 'one-time milestone')),
            'delivery_time' => trim((string) $request->input('delivery_time', '2–4 Weeks')),
            'features'      => trim((string) $request->input('features', '')),
            'cta_text'      => trim((string) $request->input('cta_text', 'Select Package')),
            'is_popular'    => $request->input('is_popular') ? 1 : 0,
            'is_active'     => $request->input('is_active', 1) ? 1 : 0,
            'sort_order'    => (int) $request->input('sort_order', 0),
        ]);

        if (function_exists('audit_log')) {
            audit_log('create_package', 'service_package', $id, "Created service package: {$title}");
        }
        flash('success', "Service package '{$title}' created successfully!");

        return Response::redirect('/admin/packages');
    }

    public function edit(Request $request, string $id): Response
    {
        $package = ServicePackage::findById((int) $id);
        if (!$package) {
            flash('error', 'Service package not found.');
            return Response::redirect('/admin/packages');
        }

        return view('admin/packages/form', [
            'package'    => $package,
            'tracks'     => ServicePackage::tracks(),
            'meta_title' => "Edit {$package['title']} | Creatsify Admin",
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        $pkgId = (int) $id;
        $package = ServicePackage::findById($pkgId);
        if (!$package) {
            flash('error', 'Service package not found.');
            return Response::redirect('/admin/packages');
        }

        $title = trim((string) $request->input('title', ''));
        if ($title === '') {
            flash('error', 'Package title is required.');
            return Response::redirect("/admin/packages/{$pkgId}/edit");
        }

        ServicePackage::update($pkgId, [
            'title'         => $title,
            'slug'          => trim((string) $request->input('slug', '')),
            'track'         => trim((string) $request->input('track', 'dev')),
            'tier_badge'    => trim((string) $request->input('tier_badge', 'Package')),
            'subtitle'      => trim((string) $request->input('subtitle', '')),
            'price_label'   => trim((string) $request->input('price_label', 'Custom')),
            'billing_cycle' => trim((string) $request->input('billing_cycle', 'one-time milestone')),
            'delivery_time' => trim((string) $request->input('delivery_time', '2–4 Weeks')),
            'features'      => trim((string) $request->input('features', '')),
            'cta_text'      => trim((string) $request->input('cta_text', 'Select Package')),
            'is_popular'    => $request->input('is_popular') ? 1 : 0,
            'is_active'     => $request->input('is_active') ? 1 : 0,
            'sort_order'    => (int) $request->input('sort_order', 0),
        ]);

        if (function_exists('audit_log')) {
            audit_log('update_package', 'service_package', $pkgId, "Updated service package: {$title}");
        }
        flash('success', "Package '{$title}' updated successfully!");

        return Response::redirect('/admin/packages');
    }

    public function toggle(Request $request, string $id): Response
    {
        $pkgId = (int) $id;
        $newState = ServicePackage::toggleActive($pkgId);
        if (function_exists('audit_log')) {
            audit_log('toggle_package', 'service_package', $pkgId, 'Toggled package active state to ' . ($newState ? 'ACTIVE' : 'INACTIVE'));
        }
        flash('success', 'Package visibility updated.');
        return Response::redirect('/admin/packages');
    }

    public function togglePopular(Request $request, string $id): Response
    {
        $pkgId = (int) $id;
        $newState = ServicePackage::togglePopular($pkgId);
        if (function_exists('audit_log')) {
            audit_log('toggle_package_popular', 'service_package', $pkgId, 'Toggled package popular badge to ' . ($newState ? 'POPULAR' : 'STANDARD'));
        }
        flash('success', 'Package Popular highlight updated.');
        return Response::redirect('/admin/packages');
    }

    public function delete(Request $request, string $id): Response
    {
        $pkgId = (int) $id;
        ServicePackage::delete($pkgId);
        if (function_exists('audit_log')) {
            audit_log('delete_package', 'service_package', $pkgId, "Deleted service package #{$pkgId}");
        }
        flash('success', 'Service package deleted.');
        return Response::redirect('/admin/packages');
    }
}
