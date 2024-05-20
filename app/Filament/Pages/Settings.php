<?php

namespace App\Filament\Pages;

use App\Models\Settings as S;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Gate;

class settings extends Page implements HasForms
{
    use InteractsWithForms;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public ?array $data = [];

    protected static string $view = 'filament.pages.settings';
    public static ?string $title = 'Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return User::permissions("settings", "view");
    }

    public function mount(\App\Models\Setting $settings): void
    {
        if (!User::permissions("settings", "view")) {
            abort(403);
        }

        try {
            $settings = $settings->orderBy('order_data')->get();

            $array = [];
            for ($i = 0; $i < count($settings->toArray()); $i++) {
                $array[$settings[$i]->toArray()["lable"]] = $settings[$i]->toArray()["value"];
            }
            $this->form->fill($array);
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function form(Form $form): Form
    {
        try {
            $settingsModel = new \App\Models\Setting();

            $settings = $settingsModel->orderBy('order_data')->get();
            $countSettings = count($settings->toArray());

            $arrayOfInputs = [];
            for ($i = 0; $i < $countSettings; $i++) {
                if ($settings[$i]->toArray()["type"] === "text")
                    $arrayOfInputs[] = TextInput::make($settings[$i]->toArray()["lable"])->required();
                if ($settings[$i]->toArray()["type"] === "bool")
                    $arrayOfInputs[] = Toggle::make($settings[$i]->toArray()["lable"])->required();
                if ($settings[$i]->toArray()["type"] === "list")
                    $arrayOfInputs[] = Select::make($settings[$i]->toArray()["lable"])
                        ->options([
                            "test" => "test",
                            "test1" => "test1",
                            "test2" => "test2"
                        ])->required();
            }

            return $form
                ->schema($arrayOfInputs)->statePath('data');
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    protected function getFormActions(): array
    {
        return [
            Action::make("save")->label("Save")->submit("save")
        ];
    }

    public function save(): void
    {
        try {
            $this->validate();

            $this->data = $this->form->getState();
            for ($i = 0; $i < count($this->data); $i++) {
                \App\Models\Setting::updateOrCreate(
                    ['lable' => array_keys($this->data)[$i]],
                    ['value' => $this->data[array_keys($this->data)[$i]]]
                );
            }

            Notification::make()->success()->title('Save Settings Successfully')->send();
        } catch (\Throwable $th) {
            Notification::make()->warning()->title('No Data Found')->send();
            dd($th);
        }
    }
}
