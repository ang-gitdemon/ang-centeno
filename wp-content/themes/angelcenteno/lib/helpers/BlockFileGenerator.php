<?php

/**
 * Generate the files needed for our custom blocks
 */
class BlockFileGenerator
{
	protected $blockType;
	protected $blockName;
	protected $blockIcon;
	protected $blockCategory;

	public function __construct(array $blockMeta)
	{
		$this->blockType = $blockMeta['name'];

		$this->blockName = array_reduce(explode('-', $this->blockType), function ($carry, $item) {
			$carry .= ucfirst($item);
			return $carry;
		});

		$this->blockIcon = $blockMeta['icon'];

		$this->blockCategory = $blockMeta['category'];
	}

	public function generate()
	{
		$blockDirPath = get_template_directory() . '/blocks/' . $this->blockType;

		if (is_dir($blockDirPath)) return;

		mkdir($blockDirPath, 0777, true);
		file_put_contents($blockDirPath . '/' . $this->blockType . '.scss', $this->get_template(get_template_directory() . '/assets/templates/block-template.scss'));
		file_put_contents($blockDirPath . '/' . $this->blockType . '.php', $this->get_template(get_template_directory() . '/assets/templates/block-template.php'));
		file_put_contents($blockDirPath . '/' . $this->blockType . '.js', $this->get_template(get_template_directory() . '/assets/templates/block-template.js'));
		file_put_contents($blockDirPath . '/block.json', $this->get_template(get_template_directory() . '/assets/templates/block.json'));
	}

	protected function get_template($templatePath)
	{
		return str_replace(
			[
				'*BLOCK_TYPE*',
				'*BLOCK_NAME*',
				'*BLOCK_ICON*',
				'*BLOCK_CAT*',
			],
			[
				$this->blockType,
				$this->blockName,
				$this->blockIcon,
				$this->blockCategory,
			],
			file_get_contents($templatePath));
	}
}
