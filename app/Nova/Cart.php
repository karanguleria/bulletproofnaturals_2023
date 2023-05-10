<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Http\Requests\NovaRequest;

class Cart extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Cart::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),
            Text::make('Email')->rules('required'),
            Text::make('Name','f_name')->sortable(),
            Text::make('Name','l_name')->sortable(),
            Text::make('Street_Address')->rules('required'),
            Text::make('Appartment')->rules('required'),
            Text::make('City')->rules('required'),
            Text::make('State')->rules('required'),
            Number::make('Zip'),
            Number::make('Phone')->rules('required'),
            Number::make('Product_Id'),
            Text::make('Product_name')->rules('required'),
            Number::make('Quantity_count'),
            Number::make('Price')->min(0.00)->max(100000000)->step(0.01),
            Number::make('Variants_Id', 'V_id'),
            Text::make('Variants_Name','V_name'),
            Number::make('Variants_Price', 'V_price')->min(0.00)->max(100000000)->step(0.01),
            Number::make('Variants_Total_Price', 'V_total_price')->min(0.00)->max(100000000)->step(0.01),
            Number::make('Variants_Month', 'V_month'),
            Number::make('Variants_Tablets', 'V_tablets'),
            Date::make('Created_at'),
            Date::make('Updated_at'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
