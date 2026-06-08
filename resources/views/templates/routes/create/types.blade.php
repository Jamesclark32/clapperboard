export type User = {
    name: string
}

export type Language = {
    messages: {
        greeting: string
    }
    fields: Record<string, string>
    form: {
        submit: string
    }
}
