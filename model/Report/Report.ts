interface Report{
	readonly id?: string,
	readonly insert_date_st: number,
	insert_date?: string,
	readonly delete_date_st: number,
	delete_date?: string,
	article_id?: string,
	reporter_email?: string,
	report_type?: string,
	claim?: string,
}

export {Report}