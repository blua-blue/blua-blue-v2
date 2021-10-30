<?php
namespace Neoan3\Model\Image;

use Neoan3\Provider\Model\ModelWrapper;
use Neoan3\Provider\Model\ModelWrapperTrait;

class ImageModelWrapper extends ImageModel implements ModelWrapper
{
	use ModelWrapperTrait;

	private ?string $id;
	private ?string $format = null;
	private ?string $path = null;
	private ?string $inserter_user_id = null;
	private string $insert_date;
	private ?string $delete_date = null;

	public function getId(): mixed
	{
		return $this->id;
	}

	public function setId($input): static
	{
		$this->id = $input;
		return $this;
	}

	public function getFormat(): mixed
	{
		return $this->format;
	}

	public function setFormat($input): static
	{
		$this->format = $input;
		return $this;
	}

	public function getPath(): mixed
	{
		return $this->path;
	}

	public function setPath($input): static
	{
		$this->path = $input;
		return $this;
	}

	public function getInserterUserId(): mixed
	{
		return $this->inserter_user_id;
	}

	public function setInserterUserId($input): static
	{
		$this->inserter_user_id = $input;
		return $this;
	}

	public function getInsertDate(): mixed
	{
		return $this->insert_date;
	}

	public function setInsertDate($input): static
	{
		$this->insert_date = $input;
		return $this;
	}

	public function getDeleteDate(): mixed
	{
		return $this->delete_date;
	}

	public function setDeleteDate($input): static
	{
		$this->delete_date = $input;
		return $this;
	}

}