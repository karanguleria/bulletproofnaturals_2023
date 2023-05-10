<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Number;
use Illuminate\Support\Facades\Storage;
use Laravel\Nova\Fields\BelongsToMany;



class Product extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Product::class;

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
            Text::make('Name')->rules('required'),
            Text::make('Slug')->rules('required'),
            Number::make('Display Price')->min(0.00)->max(100000000)->step(0.01),
            Number::make('Price')->min(0.00)->max(100000000)->step(0.01),
            Number::make('Quantity')->min(1)->max(1000),
            Image::make('Image')->disk('public')->acceptedTypes('image/*'),
            Text::make('Vedios'),
            Image::make('vedio Image')->disk('public'),
            //Text::make('images')->rules('required'),
            Trix::make('Details')->rules('required'),
            Trix::make('Details')->rules('required'),
            //Text::make('Description')->rules('required'),
            Boolean::make('Featured')
            ->trueValue('1')
            ->falseValue('0'),
            Boolean::make('Type')
            ->trueValue('1')
            ->falseValue('0'),
            Number::make('Out Of Stock')->min(1)->max(100000000)->step(1),
            BelongsToMany::make('Orders')
            ->fields(new OrderProductFields)
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
