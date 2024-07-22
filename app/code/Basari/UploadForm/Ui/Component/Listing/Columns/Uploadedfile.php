<?php
namespace Basari\UploadForm\Ui\Component\Listing\Columns;

use Magento\Catalog\Helper\Image;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class Uploadedfile extends Column
{
    const ALT_FIELD = 'title';
    protected $storeManager;

    public function __construct(
        ContextInterface      $context,
        UiComponentFactory    $uiComponentFactory,
        Image                 $imageHelper,
        UrlInterface          $urlBuilder,
        StoreManagerInterface $storeManager,
        array                 $components = [],
        array                 $data = []
    )
    {
        $this->storeManager = $storeManager;
        $this->imageHelper = $imageHelper;
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                $name = $this->getData('name');
                if (isset($item[$name])) {
                    $fileSrc = $this->storeManager->getStore()->getBaseUrl(
                            UrlInterface::URL_TYPE_MEDIA
                        ) . '/uploadedFiles/' . $item[$name];
                    $filetypeData = explode(".",$item[$name]);

                    if(in_array($filetypeData[1],['png','jpg','jpeg']))
                    {
                    $item[$name] = html_entity_decode('<a target="_blank" href="'.$fileSrc.'"><img src="'.$fileSrc.'" /></a>');
                    }
                    else
                    {
                        $item[$name] = html_entity_decode('<a target="_blank" href="'.$fileSrc.'">'.$item['title'].'</a>');
                    }
                }
            }
        }
        return $dataSource;
    }
    /*public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as &$item) {
                $url = '';
                if ($item[$fieldName] != '') {
                    $url = $this->storeManager->getStore()->getBaseUrl(
                            UrlInterface::URL_TYPE_MEDIA
                        ) . '/uploadedFiles/' . $item[$fieldName];
                }
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_orig_src'] = $url;
                $item[$fieldName . '_alt'] = $item[$fieldName];
            }
        }

        return $dataSource;
    }*/
}