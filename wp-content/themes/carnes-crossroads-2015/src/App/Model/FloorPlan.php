<?php namespace App\Model;

use HomeFinder\Model\HomeFinderFilters;

class FloorPlan
{
    public $title;
    public $description;
    public $featured_images;
    public $price;
    public $bedrooms;
    public $full_bathrooms;
    public $half_bathrooms;
    public $square_footage;
    public $brochure_src;
    public $floor_plan_src;
    public $builder;

    public static function withFilter(HomeFinderFilters $filters)
    {
        $builders = Builder::all();

        $floor_plans = array();
        foreach ($builders as $builder) {
            /* @var Builder $builder */
            $builder_floor_plans = $builder->getFloorPlans();
            $floor_plans = array_merge($floor_plans, $builder_floor_plans);
        }

        $floor_plans = array_filter($floor_plans, function ($floor_plan) use ($filters) {
            /* @var FloorPlan $floor_plan */

            if ($filters->getBuilderId() !== false) {
                if ($filters->getBuilderId() !== $floor_plan->builder->id) {
                    return false;
                }
            }

            if ($floor_plan->price < $filters->getMinPrice() || $floor_plan->price > $filters->getMaxPrice()) {
                return false;
            }

            $bedrooms = $filters->getBedrooms();
            if ($bedrooms !== false) {
                $bedrooms = array_shift($bedrooms);

                if ($floor_plan->bedrooms < $bedrooms) {
                    return false;
                }
            }

            $bathrooms = $filters->getBathrooms();
            if ($bathrooms !== false) {
                $bathrooms = array_shift($bathrooms);

                if ($floor_plan->full_bathrooms < $bathrooms) {
                    return false;
                }
            }
        });

        return $floor_plans;
    }
}