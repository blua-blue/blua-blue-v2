interface Message{
	readonly id?: string,
	readonly insert_date_st: number,
	insert_date?: string,
	readonly delete_date_st: number,
	delete_date?: string,
	subject: string,
	sent_from: string,
	content: string,
	is_read: number,
}

export {Message}