<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Date;
use Laravel\Nova\Fields\Trix;
use Laravel\Nova\Http\Requests\NovaRequest;

class AbandonedOrders extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\AbandonedOrders::class;

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
            Text::make('user_id'),
            Text::make('billing_email'),
            Trix::make('billing_address'),
            Text::make('billing_city'),
            Text::make('billing_province'),
            Text::make('billing_state'),
            Text::make('billing_country'),
            Number::make('billing_postalcode'),
            Number::make('billing_phone'),
            Text::make('billing_name_on_card'),
            Text::make('billing_discount'),
            Text::make('billing_discount_code'),
            Text::make('transection_id'),
            Text::make('receipt_url'),
            Text::make('exp_month'),
            Text::make('exp_year'),
            Text::make('last4'),
            Text::make('billing_base_total'),
            Text::make('billing_subtotal'),
            Text::make('billing_tax'),
            Text::make('billing_total'),
            Text::make('ship_different_address'),
            Text::make('diff_name'),            
            Text::make('diff_address'),
            Text::make('diff_province'),
            Text::make('diff_state'),
            Text::make('diff_city'),
            Text::make('diff_postalcode'),
            Text::make('order_notes'),            
            Text::make('payment_gateway'),
            Text::make('shipped'),
            Text::make('paypal_id'),
            Text::make('fulfillment_count'),
            Text::make('amazon_user_id'),            
            Text::make('amazon_order_reference_id'),
            Text::make('status'),
            Text::make('error'),
            Date::make('deleted_at'),
            Date::make('created_at'),
            Date::make('updated_at'),        
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