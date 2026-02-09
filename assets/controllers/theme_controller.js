import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["iconDark", "iconLight"];

    connect() {
        this.htmlElement = document.documentElement;

        // Check localStorage or system preference
        const savedTheme = localStorage.getItem('theme');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        this.currentTheme = savedTheme || (systemPrefersDark ? 'dark' : 'light');
        this.applyTheme(this.currentTheme);
    }

    toggle() {
        this.currentTheme = this.currentTheme === 'light' ? 'dark' : 'light';
        this.applyTheme(this.currentTheme);
        localStorage.setItem('theme', this.currentTheme);
    }

    applyTheme(theme) {
        this.htmlElement.setAttribute('data-bs-theme', theme);

        if (theme === 'dark') {
            this.iconDarkTarget.classList.add('d-none');
            this.iconLightTarget.classList.remove('d-none');
        } else {
            this.iconLightTarget.classList.add('d-none');
            this.iconDarkTarget.classList.remove('d-none');
        }
    }
}
