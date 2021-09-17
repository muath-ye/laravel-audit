<?php

namespace Muathye\Audit\Support;

/**
 * @method static Audit enable()
 * @method static Audit disable()
 * @method static Audit isEnabled()
 * @method static Audit audit()
 *
 * @see \Muathye\Audit\Audit
 */
class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return Audit::class;
    }
}
