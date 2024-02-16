<?php
namespace App\Listener;

use App\Entity\House;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class ImageCacheSubscriber implements EventSubscriber
{
    private $cacheManager;

    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }

    public function getSubscribedEvents()
    {
        return ['preRemove', 'preUpdate'];
    }
    public function preRemove(LifecycleEventArgs $args)
    {
        if(!$args->getObject() instanceof House)
        {
            return;
        }
        $this->cacheManager->remove($this->uploaderHelper->asset($args->getObject(), 'imageFile'));
    }
    public function preUpdate(PreUpdateEventArgs $args)
    {
        if(!$args->getEntity() instanceof House)
        {
            return;
        }
        if($args->getEntity()->getImageFile() instanceof UploadedFile)
        {
            $this->cacheManager->remove($this->uploaderHelper->asset($args->getEntity(), 'imageFile'));
        }
    }
} 