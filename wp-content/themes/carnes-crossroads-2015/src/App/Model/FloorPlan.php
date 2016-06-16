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
    public $is_master_downstairs;
    public $is_single_story;
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

    public function getPurchaseListPrice()
    {
        return $this->price;
    }

    public function getFeaturedImage()
    {
        $featured_image = $this->featured_images;
        if (is_array($featured_image) && count($featured_image) > 0) {
            $featured_image = $this->featured_images[0];
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

            $floor_plan_price = preg_replace("/[^0-9]/", "", $floor_plan->price);
            if ($filters->getMinPrice() !== false) {
                if ((int)$floor_plan_price < $filters->getMinPrice()) {
                    return false;
                }
            }

            if ($filters->getMaxPrice() !== false) {
                if ((int)$floor_plan_price > $filters->getMaxPrice()) {
                    return false;
                }
            }

            if ($filters->getBedrooms() !== false) {
                $bedrooms = $filters->getBedrooms();
                $bedrooms = explode(',', $bedrooms);
                $bedrooms = array_shift($bedrooms);

                if ((int)$floor_plan->bedrooms < (int)$bedrooms) {
                    return false;
                }
            }


            if ($filters->getBathrooms() !== false) {
                $bathrooms = $filters->getBathrooms();
                $bathrooms = explode(',', $bathrooms);
                $bathrooms = array_shift($bathrooms);
                if ((int)$floor_plan->full_bathrooms < (int)$bathrooms) {
                    return false;
                }
            }

            if (false !== $filters->getHomeFeatures()) {
                $homeFeatures = $filters->getHomeFeatures();

                if (stristr($homeFeatures, 'master') !== false) {
                    if ($floor_plan->is_master_downstairs === false) {
                        return false;
                    }
                }

                if (stristr($homeFeatures, 'single') !== false) {
                    if ($floor_plan->is_single_story === false) {
                        return false;
                    }
                }
            }

            return true;

        });

        return $floor_plans;
    }
}