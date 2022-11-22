


const fields = document.getElementsByClassName('searchFormField');
for (let field of fields) {
    field.addEventListener('change', () => {
        document.forms['searchForm'].submit();
    })
}