import { usePage } from '@inertiajs/vue3';
import type { ComputedRef, Ref } from 'vue';
import { computed, getCurrentInstance, onMounted, ref } from 'vue';

export type Appearance = 'light' | 'dark' | 'system';
export type ResolvedAppearance = 'light' | 'dark';

export type UseAppearanceReturn = {
    appearance: Ref<Appearance>;
    resolvedAppearance: ComputedRef<ResolvedAppearance>;
    updateAppearance: (value: Appearance) => void;
};

const appearances: readonly Appearance[] = ['light', 'dark', 'system'];

const isAppearance = (value: unknown): value is Appearance =>
    appearances.includes(value as Appearance);

/**
 * The appearance the interface renders. It deliberately matches what the server
 * rendered until the app has mounted, so hydration never sees a mismatch.
 */
const appearance = ref<Appearance>('system');

/** The operating system preference, in a ref so resolvedAppearance is reactive. */
const systemPrefersDark = ref(false);

const mediaQuery = (): MediaQueryList | null => {
    if (typeof window === 'undefined') {
        return null;
    }

    return window.matchMedia('(prefers-color-scheme: dark)');
};

const readCookie = (name: string): string | null => {
    if (typeof document === 'undefined') {
        return null;
    }

    const match = document.cookie.match(
        new RegExp(`(?:^|;\\s*)${name}=([^;]*)`),
    );

    return match?.[1] ?? null;
};

/** The value the server rendered from, so the first client render can match it. */
const readRenderedAppearance = (): Appearance => {
    const cookie = readCookie('appearance');

    return isAppearance(cookie) ? cookie : 'system';
};

/**
 * The durable preference, or null when the visitor has never chosen one.
 *
 * The cookie can expire behind localStorage, so localStorage wins when the two disagree.
 */
const readStoredAppearance = (): Appearance | null => {
    if (typeof window === 'undefined') {
        return null;
    }

    const stored = localStorage.getItem('appearance');

    if (isAppearance(stored)) {
        return stored;
    }

    const cookie = readCookie('appearance');

    return isAppearance(cookie) ? cookie : null;
};

const setCookie = (name: string, value: string, days = 365): void => {
    if (typeof document === 'undefined') {
        return;
    }

    const maxAge = days * 24 * 60 * 60;

    document.cookie = `${name}=${value};path=/;max-age=${maxAge};SameSite=Lax`;
};

const resolve = (value: Appearance): ResolvedAppearance => {
    if (value === 'system') {
        return systemPrefersDark.value ? 'dark' : 'light';
    }

    return value;
};

/** The only place that touches the class on the <html> element. */
const applyTheme = (value: Appearance): void => {
    document.documentElement.classList.toggle(
        'dark',
        resolve(value) === 'dark',
    );
};

/** The only writer: the ref, both stores, and the DOM. */
const persistAppearance = (value: Appearance): void => {
    appearance.value = value;

    if (typeof window === 'undefined') {
        return;
    }

    localStorage.setItem('appearance', value);

    // The server renders from this cookie. Rewriting it on every visit keeps it
    // from quietly expiring behind localStorage...
    setCookie('appearance', value);

    applyTheme(value);
};

export function initializeTheme(): void {
    const query = mediaQuery();

    if (!query) {
        return;
    }

    systemPrefersDark.value = query.matches;

    // Start from what the server rendered, so the first client render agrees
    // with the markup it is hydrating...
    appearance.value = readRenderedAppearance();

    // The <html> class is outside Vue's control, so the durable preference can
    // be applied straight away without upsetting hydration...
    applyTheme(readStoredAppearance() ?? 'system');

    query.addEventListener('change', (event) => {
        systemPrefersDark.value = event.matches;

        applyTheme(appearance.value);
    });
}

export function useAppearance(): UseAppearanceReturn {
    const { appearance: serverAppearance } = usePage().props;

    // initializeTheme() cannot run while server side rendering, and the module
    // state is shared between requests there, so seed from this request...
    if (typeof window === 'undefined' && isAppearance(serverAppearance)) {
        appearance.value = serverAppearance;
    }

    // Hydration is finished by the time a component mounts, so the durable
    // preference can now be adopted even when the cookie had expired...
    if (getCurrentInstance()) {
        onMounted(() => {
            const stored = readStoredAppearance();

            if (stored !== null) {
                persistAppearance(stored);
            }
        });
    }

    const resolvedAppearance = computed<ResolvedAppearance>(() =>
        resolve(appearance.value),
    );

    return {
        appearance,
        resolvedAppearance,
        updateAppearance: persistAppearance,
    };
}
