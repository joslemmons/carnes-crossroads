<?php

namespace App\Model;

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

    public function fullLink()
    {
        $slug       = new Slugify();
        $builder    = $slug->slugify($this->builder->title());
        $floor_plan = $slug->slugify($this->title);
        return home_url().'/home-finder/floor-plans/'.$builder.'/'.$floor_plan.'/';
    }

    public function getId()
    {
        return $this->title;
    }

    public function getPropertyType()
    {
        return 'Floor Plan';
    }

    public function getAddress()
    {
        return $this->title;
    }

    public function isFromMLS()
    {
        return false;
    }

    public function link()
    {
        $slug       = new Slugify();
        $builder    = $slug->slugify($this->builder->title());
        $floor_plan = $slug->slugify($this->title);
        return '/home-finder/floor-plans/'.$builder.'/'.$floor_plan.'/';
    }

    public function getTotalAreaSquareFootageUnitOfMeasurement()
    {
        return 'SQ FT';
    }

    public function getTotalAreaSquareFootage()
    {
        return $this->square_footage;
    }

    public function getBedroomCount()
    {
        if (is_array($this->bedrooms) && empty($this->bedrooms)) {
            return '0';
        }

        return $this->bedrooms;
    }

    public function getHalfBathroomCount()
    {
        if (is_array($this->half_bathrooms) && empty($this->half_bathrooms)) {
            return '0';
        }

        return $this->half_bathrooms;
    }

    public function getFullBathroomCount()
    {
        if (is_array($this->full_bathrooms) && empty($this->full_bathrooms)) {
            return '0';
        }

        return $this->full_bathrooms;
    }

    public function getImages()
    {
        return $this->featured_images;
    }

    public function getPurchaseListPrice()
    {
        return $this->price;
    }

    public function getFriendlyName()
    {
        return sprintf('%s, %s Bedrooms, %s Full Bathrooms, $%s', $this->title,
            $this->bedrooms, $this->full_bathrooms,
            number_format(preg_replace("/[^0-9]/", "", $this->price))
        );
    }

    public function getFeaturedImageSrc()
    {
        return $this->getFeaturedImage();
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
            $floor_plans         = array_merge($floor_plans,
                $builder_floor_plans);
        }

        $slugify     = new Slugify();
        $floor_plans = array_filter($floor_plans,
            function ($floor_plan) use ($filters, $slugify) {
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
                if ((int) $floor_plan_price < $filters->getMinPrice()) {
                    return false;
                }
            }

            if ($filters->getMaxPrice() !== false) {
                if ((int) $floor_plan_price > $filters->getMaxPrice()) {
                    return false;
                }
            }

            if ($filters->getBedrooms() !== false) {
                $bedrooms = $filters->getBedrooms();
                $bedrooms = explode(',', $bedrooms);
                $bedrooms = array_shift($bedrooms);

                if ((int) $floor_plan->bedrooms < (int) $bedrooms) {
                    return false;
                }
            }


            if ($filters->getBathrooms() !== false) {
                $bathrooms = $filters->getBathrooms();
                $bathrooms = explode(',', $bathrooms);
                $bathrooms = array_shift($bathrooms);
                if ((int) $floor_plan->full_bathrooms < (int) $bathrooms) {
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

            $floor_plan_sqft = preg_replace("/[^0-9]/", "",
                $floor_plan->square_footage);
            if ($filters->getMinSquareFootage() !== false) {
                if ((int) $floor_plan_sqft < $filters->getMinSquareFootage()) {
                    return false;
                }
            }

            if ($filters->getMaxSquareFootage() !== false) {
                if ((int) $floor_plan_sqft > $filters->getMaxSquareFootage()) {
                    return false;
                }
            }

            return true;
        });

        return $floor_plans;
    }
}