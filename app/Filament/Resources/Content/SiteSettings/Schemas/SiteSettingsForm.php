<?php

namespace App\Filament\Resources\Content\SiteSettings\Schemas;

use App\Models\Content\SiteSetting;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Illuminate\Support\HtmlString;

class SiteSettingsForm
{
    /** @return array<string, array{label: string, type: string, component: string}> */
    public static function definitions(): array
    {
        return [
            'hero_title' => ['label' => 'Hero title', 'type' => 'text', 'component' => 'text'],
            'hero_description' => ['label' => 'Hero description', 'type' => 'textarea', 'component' => 'textarea'],
            'facebook_url' => ['label' => 'Facebook URL', 'type' => 'url', 'component' => 'url'],
        ];
    }

    public static function configure(Schema $schema): Schema
    {
        $translationSections = collect(self::definitions())
            ->map(fn (array $definition, string $key): Section => Section::make($definition['label'])
                ->description("Fixed key: {$key}")
                ->schema([
                    Tabs::make("{$key}_translations")->tabs([
                        Tab::make('العربية')->schema([self::field("{$key}.ar", 'القيمة', $definition['component'])]),
                        Tab::make('English')->schema([self::field("{$key}.en", 'Value', $definition['component'])]),
                    ]),
                ]))
            ->values()
            ->all();

        return $schema->components([
            Section::make('Site logo')
                ->description('Fixed key: site_logo')
                ->schema([
                    Placeholder::make('current_site_logo')
                        ->label('Current logo')
                        ->content(function (): HtmlString {
                            $url = SiteSetting::query()->where('key', 'site_logo')->first()?->getFirstMediaUrl('logo');

                            return new HtmlString($url ? '<img src="'.e($url).'" alt="Current site logo" style="max-height: 10rem; border-radius: .5rem">' : 'No logo uploaded.');
                        }),
                    FileUpload::make('site_logo_upload')
                        ->label('Replace logo')
                        ->image()
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml', 'image/x-icon'])
                        ->maxSize(4096)
                        ->storeFiles(false),
                ]),
            ...$translationSections,
        ]);
    }

    private static function field(string $name, string $label, string $component): TextInput|Textarea
    {
        $field = $component === 'textarea'
            ? Textarea::make($name)->rows(4)
            : TextInput::make($name);

        return $field
            ->label($label)
            ->required()
            ->when($component === 'url', fn (TextInput|Textarea $field) => $field->url())
            ->maxLength($component === 'textarea' ? 1000 : 255);
    }
}
