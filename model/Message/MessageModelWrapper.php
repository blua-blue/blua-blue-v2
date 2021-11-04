<?php
namespace Neoan3\Model\Message;

use Neoan3\Provider\Model\ModelWrapper;
use Neoan3\Provider\Model\ModelWrapperTrait;

class MessageModelWrapper extends MessageModel implements ModelWrapper
{
	use ModelWrapperTrait;

	private ?string $id;
	private ?string $insert_date;
	private ?string $delete_date = null;
	private string $subject;
	private string $sent_from;
	private string $content;
	private int $is_read;

	public function getId(): ?string
    {
		return $this->id;
	}

	public function setId($input): static
	{
		$this->id = $input;
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

	public function getSubject(): mixed
	{
		return $this->subject;
	}

	public function setSubject($input): static
	{
		$this->subject = $input;
		return $this;
	}

	public function getSentFrom(): mixed
	{
		return $this->sent_from;
	}

	public function setSentFrom($input): static
	{
		$this->sent_from = $input;
		return $this;
	}

	public function getContent(): mixed
	{
		return $this->content;
	}

	public function setContent($input): static
	{
		$this->content = $input;
		return $this;
	}

	public function getIsRead(): mixed
	{
		return $this->is_read;
	}

	public function setIsRead($input): static
	{
		$this->is_read = $input;
		return $this;
	}

}