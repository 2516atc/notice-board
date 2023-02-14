import { sanitize } from 'dompurify';
import { marked } from 'marked';

const markdownToHtml = (markdown: string) =>
    sanitize(marked(markdown));

export { markdownToHtml };
