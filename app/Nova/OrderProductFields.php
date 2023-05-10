<?php 
namespace App\Nova;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class  OrderProductFields{
    public function __invoke()
    {
        return [
            Number::make('quantity'),
            Text::make('variant'),
            Text::make('months'),
            Text::make('price'),
            Text::make('type'),

        ];
    }
}
