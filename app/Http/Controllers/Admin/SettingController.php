<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\HandlesUploads;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\SettingGroups;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use HandlesUploads;

    public function edit(string $group)
    {
        $definition = SettingGroups::find($group) ?? abort(404);

        return view('admin.settings.edit', [
            'group' => $group,
            'definition' => $definition,
        ]);
    }

    public function update(Request $request, string $group)
    {
        $definition = SettingGroups::find($group) ?? abort(404);
        $labels = array_map(fn ($field) => strtolower($field['label']), $definition['fields']);
        $data = $request->validate(SettingGroups::rules($group), [], $labels);

        $values = [];
        foreach ($definition['fields'] as $key => $field) {
            $values[$key] = $field['type'] === 'image'
                ? $this->storeImage($request, $key, setting($key), 'pengaturan')
                : ($data[$key] ?? null);
        }

        Setting::setMany($values);

        return back()->with('success', $definition['title'].' berhasil disimpan.');
    }
}
