<?php namespace App\Model;

use HomeFinder\Model\HomeFinderFilters;

class FloorPlan
{
    public $isFloorPlan = true;

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

    public function getFeaturedImage()
    {
        $featured_image = $this->featured_images;
        if (is_array($featured_image)) {
            $featured_image = array_shift($this->featured_images);
        }

        return $featured_image;
    }

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

            if ($filters->getMinPrice() !== false && $filters->getMaxPrice() !== false) {
            if ($floor_plan->price < $filters->getMinPrice() || $floor_plan->price > $filters->getMaxPrice()) {
                return false;
            }
            }


            if ($filters->getBedrooms() !== false) {
            $bedrooms = $filters->getBedrooms();
            if ($bedrooms !== false) {
                $bedrooms = array_shift($bedrooms);

                if ($floor_plan->bedrooms < $bedrooms) {
                    return false;
                }
            }

            }


            if ($filters->getBathrooms() !== false) {
            $bathrooms = $filters->getBathrooms();
            if ($bathrooms !== false) {
                $bathrooms = array_shift($bathrooms);

                if ($floor_plan->full_bathrooms < $bathrooms) {
                    return false;
                }
            }
            }

            return true;

        });

        return $floor_plans;
    }
}