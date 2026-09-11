<?php

namespace App\Filament\Resources\People\Schemas;

use App\Support\PersonPhoto;
use Filament\Forms\Components\BaseFileUpload;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class PersonForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required()
                    ->maxLength(80),

                TextInput::make('last_name')
                    ->required()
                    ->maxLength(80),

                DateTimePicker::make('birth_datetime')
                    // Have Filament render the date in the same timezone it was parsed with,
                    // to ensure that we preserve the plain date and time values as-is.
                    ->timezone(date_default_timezone_get())
                    ->required(),

                Select::make('birth_timezone')
                    ->required()
                    ->options(array_combine(timezone_identifiers_list(), timezone_identifiers_list()))
                    ->searchable(),

                TextInput::make('residence')
                    ->maxLength(100),

                TextInput::make('phone')
                    ->tel()
                    ->maxLength(50),

                TextInput::make('email')
                    ->email()
                    ->maxLength(100),

                FileUpload::make('photo')
                    ->disk(PersonPhoto::DISK)
                    ->directory(PersonPhoto::DIRECTORY)

                    // --------------------------------
                    // The 3 next instructions serve one purpose: store only the photo file name, not the full storage
                    // path (with the directory prefix), to decouple the file storage from the model as much as
                    // possible. Callback arguments' names and types are important: they are used for dependency
                    // injection. `fetchFileInformation(false)` is load-bearing: on load, `hydrateFiles()` drops any
                    // state value missing from the disk, checking the raw column value (a bare file name) so every
                    // photo would silently vanish from the field. It exposes no callback to rebuild the path first.
                    // Trade-off: a row pointing at a deleted file now renders broken instead of empty.
                    ->saveUploadedFileUsing(function (BaseFileUpload $component, TemporaryUploadedFile $file): ?string {
                        $path = $component->saveUploadedFile($file);

                        return $path !== null ? basename($path) : null;
                    })
                    ->getUploadedFileUsing(function (BaseFileUpload $component, string $file, string|array|null $storedFileNames): ?array {
                        return $component->getUploadedFile(PersonPhoto::path($file), $storedFileNames);
                    })
                    ->fetchFileInformation(false)
                    // --------------------------------

                    ->preventFilePathTampering()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/avif'])
                    ->imageEditor()
                    ->imageAspectRatio('1:1')
                    ->automaticallyOpenImageEditorForAspectRatio()
                    ->automaticallyResizeImagesToWidth('1024')
                    ->automaticallyResizeImagesToHeight('1024')
                    ->automaticallyResizeImagesMode('cover')
                    ->automaticallyUpscaleImagesWhenResizing(false)
                    ->automaticallyCropImagesToAspectRatio()
                    // We want to allow large raw inputs (16 MiB, before resize) to be submitted, while capping the
                    // final file we store at 1 MiB. `maxSize()` sets the limit for both client-side and server-side,
                    // so we need to append a second, stricter server-side validation rule to make that happen.
                    ->maxSize(16384)
                    ->rule('max:1024')
                    ->rule(Rule::dimensions()->maxWidth(1024)->maxHeight(1024)->ratio(1)),
            ]);
    }
}
