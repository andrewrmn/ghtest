<?php
namespace BBModules;
abstract class GHBBModule extends \FLBuilderModule
{
    abstract function getName() : string;
    abstract static function getSettings() : array;
    final protected function getSlug() : string
    {
        $className = explode('\\', static::class );
        return array_pop( $className );
    }

    final public static function register() : void
    {
        $settings = static::getSettings();
        $class    = static::class;

        \FLBuilder::register_module( $class, $settings );
    }
    public function __construct(  ) {

        $name = $this->getName();

        parent::__construct([
            'name' => $name,
            'description' => '',
            'group' => 'Gifted Health',
            'category' => 'Gifted Health',
            'icon' => 'button.svg',
            'include_wrapper' => true,
        ]);
    }
}
