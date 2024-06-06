<?php
namespace App\Traits;

trait CanFlashMessage{
    /**
     * @param $message
     * @param string $type
     */
    public function flashMessage($message, $type = 'positive')
    {
        session()->flash('flash-notification',[
            'type'=>$type,
            'message'=>$message
        ]);
    }

    public function addBreadcrumbs(array $arr)
    {
        session()->remove('breadcrumbs');
        session()->push('breadcrumbs',$arr);
    }
}
