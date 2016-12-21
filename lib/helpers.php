<?php


if (! function_exists('app'))
{
    /**
     * Get the available container instance.
     *
     * @param  string  $make
     * @param  array   $parameters
     * @return mixed
     */
    function app($make = null, $parameters = [])
    {
        if (is_null($make)) {
            return Container::getInstance();
        }

        return Container::getInstance()->make($make, $parameters);
    }
}


if (! function_exists('tbl_trans'))
{
    /**
     * Translate the given message.
     *
     * @param  string  $id
     * @param  array   $parameters
     * @param  string  $domain
     * @param  string  $locale
     * @return \Symfony\Component\Translation\TranslatorInterface|string
     */
    function tbl_trans($id = null, $parameters = [], $domain = 'messages', $locale = null)
    {
        if (is_null($id)) {
            return app('translator');
        }

        $key = _TLB_PKG_NAME_ . '::lang.' . $id;

        return app('translator')->trans($key, $parameters, $domain, $locale);
    }
}

//
if (! function_exists('tbl_storage_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function tbl_storage_path($path = '')
    {
        $path = tbl_storage_path($path);
        return app('path.storage').($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}


if (! function_exists('tbl_project_path')) {
    /**
     * Get the path to the storage folder.
     *
     * @param  string  $path
     * @return string
     */
    function tbl_project_path($path = '')
    {
        return 'toolsets/builder' .($path ? DIRECTORY_SEPARATOR.$path : $path);
    }
}