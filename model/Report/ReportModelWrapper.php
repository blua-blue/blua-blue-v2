<?php
namespace Neoan3\Model\Report;

use Neoan3\Provider\Model\ModelWrapper;
use Neoan3\Provider\Model\ModelWrapperTrait;

class ReportModelWrapper extends ReportModel implements ModelWrapper
{
	use ModelWrapperTrait;

	private ?string $id;
	private ?string $insert_date;
	private ?string $delete_date = null;
	private string $article_id;
	private ?string $reporter_email = null;
	private ?string $report_type = null;
	private ?string $claim = null;

	public function getId(): mixed
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

	public function getArticleId(): mixed
	{
		return $this->article_id;
	}

	public function setArticleId($input): static
	{
		$this->article_id = $input;
		return $this;
	}

	public function getReporterEmail(): mixed
	{
		return $this->reporter_email;
	}

	public function setReporterEmail($input): static
	{
		$this->reporter_email = $input;
		return $this;
	}

	public function getReportType(): mixed
	{
		return $this->report_type;
	}

	public function setReportType($input): static
	{
		$this->report_type = $input;
		return $this;
	}

	public function getClaim(): mixed
	{
		return $this->claim;
	}

	public function setClaim($input): static
	{
		$this->claim = $input;
		return $this;
	}

}