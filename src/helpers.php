<?php

use Muathye\Audit\Support\Audit as SupportAudit;

if (! function_exists('audit')) {
    /**
     * Returns the Carbon instance
     *
     * @return void
     */
    function audit()
    {
        SupportAudit::audit();
    }
}

if (! function_exists('enableAudit')) {
    /**
     * Returns the Carbon instance
     *
     * @return void
     */
    function enableAudit()
    {
        resolve('audit')->enable();
    }
}

if (! function_exists('disableAudit')) {
    /**
     * Returns the Carbon instance
     *
     * @return void
     */
    function disableAudit()
    {
        // resolve('muathyeAudit')->disable();
        resolve('audit')->disable();
    }
}
