const theming = (color, disabled, animated) => {
    const classes = {};
    ['primary', 'accent', 'warn', 'warning', 'success', 'black'].forEach(computed => {

        ['light','lighter','dark','darker'].forEach(modifier => {
            classes[`bg-${computed}-${modifier} b-${computed}-${modifier} b-1 hover:raise-1-gray-light text-white`] = color === `${computed}-${modifier}-filled`
            classes[`b-${computed}-${modifier} b-1 bg-transparent text-${computed}-${modifier}`] = color === `${computed}-${modifier}`;
            classes[`hover:bg-${computed}-${modifier} hover:text-white`] = color === `${computed}-${modifier}` && !disabled;
        })
        classes[`bg-${computed} b-${computed} b-1 hover:raise-1-gray-light text-white`] = color === `${computed}-filled`
        classes[`b-${computed} b-1 bg-transparent text-${computed}`] = color === `${computed}`;
        classes[`hover:bg-${computed} hover:text-white`] = color === `${computed}` && !disabled;
    })
    classes['cursor-pointer'] = !disabled;
    classes['cursor-not-allowed'] = disabled;
    classes['grow-x-infinite'] = animated
    return classes;
};