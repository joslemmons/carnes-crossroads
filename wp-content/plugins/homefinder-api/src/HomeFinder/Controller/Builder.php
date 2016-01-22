<?php namespace HomeFinder\Controller;

use App\Controller\Router;

class Builder extends Router
{
    public static function routeGetFloorPlan($params = array())
    {
        $builder_name = (isset($params['builder_name'])) ? str_replace('-', ' ', sanitize_text_field($params['builder_name'])) : false;
        $floor_plan_title = (isset($params['floor_plan_title'])) ? str_replace('-', ' ', sanitize_text_field($params['floor_plan_title'])) : false;

        $builder = \App\Model\Builder::withName($builder_name);

        if (!$builder) {
            self::renderJSON(array(
                'rsp' => 'Unable to find Builder'
            ), 404);
        }

        $floor_plan = $builder->getFloorPlanByName($floor_plan_title);

        if (!$floor_plan) {
            self::renderJSON(array(
                'rsp' => 'Unable to find Floor Plan'
            ), 404);
        }

        self::renderJSON(array(
            'rsp' => \Timber::compile('partials/home-finder/floor-plan.twig', array(
                'floor_plan' => $floor_plan,
                'template_uri' => get_template_directory_uri()
            ))
        ));
    }

    public static function routeShowFloorPlan($params = array())
    {

    }
}