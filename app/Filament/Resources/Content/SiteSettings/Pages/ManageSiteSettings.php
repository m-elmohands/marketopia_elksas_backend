<?php

namespace App\Filament\Resources\Content\SiteSettings\Pages;

use App\Filament\Resources\Content\SiteSettings\Schemas\SiteSettingsForm;
use App\Filament\Resources\Content\SiteSettings\SiteSettingResource;
use App\Models\Content\SiteSetting;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Gate;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ManageSiteSettings extends Page
{
    protected static string $resource = SiteSettingResource::class;

    /** @var array<string, mixed> */
    public ?array $data = [];

    public function mount(): void
    {
        $data = collect(SiteSettingsForm::definitions())
            ->mapWithKeys(fn (array $definition, string $key): array => [$key => ['ar' => null, 'en' => null]])
            ->all();
        $data['site_logo_upload'] = null;

        SiteSetting::query()
            ->with('translations')
            ->whereIn('key', array_keys(SiteSettingsForm::definitions()))
            ->get()
            ->each(function (SiteSetting $setting) use (&$data): void {
                $data[$setting->key] = [
                    'ar' => $setting->translate('ar')?->value,
                    'en' => $setting->translate('en')?->value,
                ];
            });

        $this->form->fill($data);
    }

    public function form(Schema $schema): Schema
    {
        return SiteSettingsForm::configure($schema)->statePath('data');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')
                            ->label('Save settings')
                            ->submit('save')
                            ->keyBindings(['mod+s']),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (SiteSettingsForm::definitions() as $key => $definition) {
            $setting = SiteSetting::query()->firstOrNew(['key' => $key]);
            Gate::authorize($setting->exists ? 'update' : 'create', $setting->exists ? $setting : SiteSetting::class);

            $setting->fill([
                'type' => $definition['type'],
                'ar' => ['value' => $data[$key]['ar']],
                'en' => ['value' => $data[$key]['en']],
            ])->save();
        }

        $logoSetting = SiteSetting::query()->firstOrNew(['key' => 'site_logo']);
        Gate::authorize($logoSetting->exists ? 'update' : 'create', $logoSetting->exists ? $logoSetting : SiteSetting::class);
        $logoSetting->fill(['type' => 'image'])->save();

        $logoUpload = $data['site_logo_upload'] ?? null;

        if ($logoUpload instanceof TemporaryUploadedFile) {
            $logoSetting->addMedia($logoUpload->getRealPath())
                ->usingFileName($logoUpload->getClientOriginalName())
                ->toMediaCollection('logo');
        }

        Notification::make()->success()->title('Settings saved')->send();
    }
}
