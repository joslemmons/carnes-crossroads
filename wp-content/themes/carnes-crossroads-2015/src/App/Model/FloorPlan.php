<?php namespace App\Model;

use Cocur\Slugify\Slugify;
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

    public function link()
    {
        $slug = new Slugify();
        $builder = $slug->slugify($this->builder->title());
        $floor_plan = $slug->slugify($this->title);
        return 'home-finder/floor-plans/' . $builder . '/' . $floor_plan . '/';
    }

    public function getImages()
    {
        return $this->featured_images;
    }

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

        $slugify = new Slugify();
        $floor_plans = array_filter($floor_plans, function ($floor_plan) use ($filters, $slugify) {
            /* @var FloorPlan $floor_plan */

            if ($filters->getBuilders() !== false) {
                $builders = $filters->getBuilders();
                $builders = explode(',', $builders);
                $builders = array_shift($builders);

                $filter_builder = $slugify->slugify($floor_plan->builder->title());

                if (trim($builders) !== trim($filter_builder)) {
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
                $bedrooms = explode(',', $bedrooms);
                $bedrooms = array_shift($bedrooms);

                if ($floor_plan->bedrooms < $bedrooms) {
                    return false;
                }
            }


            if ($filters->getBathrooms() !== false) {
                $bathrooms = $filters->getBathrooms();
                $bathrooms = explode(',', $bathrooms);
                $bathrooms = array_shift($bathrooms);
                if ($floor_plan->full_bathrooms < $bathrooms) {
                    return false;
                }
            }

            return true;

        });

        return $floor_plans;
    }
}