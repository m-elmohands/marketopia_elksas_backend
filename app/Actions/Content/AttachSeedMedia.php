<?php

namespace App\Actions\Content;

use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Spatie\MediaLibrary\HasMedia;

class AttachSeedMedia
{
    /** @param Model&HasMedia $record */
    public function execute(Model $record, string $collection, string $relativePath): void
    {
        if ($record->getMedia($collection)->isNotEmpty()) {
            return;
        }

        $path = database_path("seeders/media/{$relativePath}");

        if (! is_file($path)) {
            throw new RuntimeException("Seed media file does not exist: {$relativePath}");
        }

        $record
            ->addMedia($path)
            ->preservingOriginal()
            ->usingName(pathinfo($relativePath, PATHINFO_FILENAME))
            ->withCustomProperties([
                'source' => 'facebook',
                'facebook_page_id' => '100064234203552',
                'source_asset' => basename($relativePath),
            ])
            ->toMediaCollection($collection);
    }
}
