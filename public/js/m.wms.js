function goTo(module) {
    switch(module) {
        case 'received':
            window.location.href = '/received';
            break;

        case 'putaway':
            window.location.href = '/putaway';
            break;

        case 'picking':
            window.location.href = '/picking';
            break;

        default:
            alert('Module tidak ditemukan');
    }
}