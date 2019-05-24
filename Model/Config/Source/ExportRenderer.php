<?php
/**
 * Copyright © OpenGento, All rights reserved.
 * See LICENSE bundled with this library for license details.
 */
declare(strict_types=1);

namespace Opengento\Gdpr\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\ObjectManager\ConfigInterface;
use Magento\Framework\Phrase;
use Opengento\Gdpr\Service\Export\RendererFactory;

/**
 * Class ExportRenderer
 */
class ExportRenderer implements OptionSourceInterface
{
    /**
     * @var \Magento\Framework\ObjectManager\ConfigInterface
     */
    private $objectManagerConfig;

    /**
     * @var array
     */
    private $options;

    /**
     * @param \Magento\Framework\ObjectManager\ConfigInterface $objectManagerConfig
     */
    public function __construct(
        ConfigInterface $objectManagerConfig
    ) {
        $this->objectManagerConfig = $objectManagerConfig;
    }

    /**
     * @inheritdoc
     */
    public function toOptionArray(): array
    {
        if (!$this->options) {
            foreach (\array_keys($this->retrieveRenderers()) as $rendererName) {
                $this->options[] = ['label' => new Phrase($rendererName), 'value' => $rendererName];
            }
        }

        return $this->options;
    }

    /**
     * Retrieve the renderers from the di settings
     *
     * @return string[]
     */
    private function retrieveRenderers(): array
    {
        $arguments = $this->objectManagerConfig->getArguments(
            $this->objectManagerConfig->getPreference(RendererFactory::class)
        );

        return $arguments['renderers']['_v_'] ?? $arguments['renderers'] ?? [];
    }
}
