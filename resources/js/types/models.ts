export interface Person {
    first_name: string;
    last_name: string;
    full_name: string;
    age?: number | null;
    residence?: string | null;
    phone?: string | null;
    email?: string | null;
}

export interface CurriculumVitae {
    slug: string;
    person: Person;
    headline: string | null;
    summary: string | null;
}
