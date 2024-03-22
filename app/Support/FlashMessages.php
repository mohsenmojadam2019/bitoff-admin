<?php


namespace App\Support;


use Illuminate\Support\Arr;

trait FlashMessages
{
    protected $flashMessageTitle = 'alert';
    private $iconMap = [
        'info' => 'fas fa-info',
        'warning' => 'fas fa-exclamation-triangle',
        'danger' => 'fas fa-ban',
        'success' => 'fas fa-check',
    ];

    /**
     * @param $type
     * @param $message
     * @param string $title
     * @return FlashMessages
     */
    public function message($type, $message, $title)
    {
        $type = in_array($type, array_keys($this->iconMap)) ? $type : 'info';

        $flash = Arr::prepend(session()->get("flash-message", []), [
            'title' => $title,
            'type' => $type,
            'message' => $message,
            'icon' => $this->iconMap[$type]
        ]);

        session()->flash("flash-message", array_reverse($flash));
        return $this;
    }

    /**
     * @param $message
     * @param null $title
     * @return FlashMessages
     */
    public function error($message, $title = null)
    {
        return $this->message('danger', $message, $title ?: $this->flashMessageTitle);
    }

    /**
     * @param $message
     * @param null $title
     * @return FlashMessages
     */
    public function info($message, $title = null)
    {
        return $this->message('info', $message, $title ?: $this->flashMessageTitle);
    }

    /**
     * @param $message
     * @param null $title
     * @return FlashMessages
     */
    public function success($message, $title = null)
    {
        return $this->message('success', $message, $title ?: $this->flashMessageTitle);
    }

    /**
     * @param $message
     * @param null $title
     * @return FlashMessages
     */
    public function warning($message, $title = null)
    {
        return $this->message('warning', $message, $title ?: $this->flashMessageTitle);
    }

}
