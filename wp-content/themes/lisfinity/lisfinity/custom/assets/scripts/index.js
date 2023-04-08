import '../css/index.css';

const el = document.getElementById('main-search');

if(el) {
    el.addEventListener('submit', (e) => {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(e.target).entries());
        let query = new URLSearchParams(data).toString();
        let url = e.target.action + '?' + query;
        el.submit();
    });
}
