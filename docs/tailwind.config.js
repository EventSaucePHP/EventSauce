const defaultColors = require('tailwindcss/colors')

let colors = {
    'transparent': 'transparent',

    'for-code': '#253238',

    'red': '#BE2323',
    'red-lighter': '#BE2323',

    'black': '#22292f',
    'grey-darkest': '#3d4852',
    'grey-darker': '#606f7b',
    'grey-dark': '#8795a1',
    'grey': '#b8c2cc',
    'grey-light': '#dae1e7',
    'grey-lighter': '#f1f5f8',
    'grey-lightest': '#f8fafc',
    'white': '#ffffff',

    'gray': defaultColors.zinc,
};

module.exports = {
    prefix: '',
    mode: 'jit',
    important: false,
    separator: ':',
    purge: [
        './_site/**/*.html',
    ],
    theme: {
        extend: {
            colors: colors,
            screens: {
                'sm': '576px',
                '-md': { 'max': '768px' },
                'sm-md': { 'min': '576px', 'max': '768px' },
                'md': '768px',
                'md-lg': { 'min': '768px', 'max': '992px' },
                '-lg': { 'max': '992px' },
                'lg': '992px',
                'lg-xl': { 'min': '992px', 'max': '1200px' },
                'xl': '1200px',
            },
            fontFamily: {
                'heading': [
                    'Varela Round',
                    'sans-serif'
                ],
                'body': [
                    'Raleway',
                    'sans-serif'
                ]
            },

            fontSize: {
                'xs': '.75rem',     // 12px
                'sm': '.875rem',    // 14px
                'base': '1rem',     // 16px
                'lg': '1.125rem',   // 18px
                'xl': '1.25rem',    // 20px
                '2xl': '1.5rem',    // 24px
                '3xl': '1.875rem',  // 30px
                '4xl': '2.25rem',   // 36px
                '5xl': '3rem',      // 48px
            },
            fontWeight: {
                'hairline': 100,
                'thin': 200,
                'light': 300,
                'normal': 400,
                'medium': 500,
                'semibold': 600,
                'bold': 700,
                'extrabold': 800,
                'black': 900,
            },
            lineHeight: {
                'squash': 0,
                'none': 1,
                'tight': 1.25,
                'normal': 1.5,
                'loose': 2,
                'very-loose': 3.5,
            },
            letterSpacing: {
                'tight': '-0.05em',
                'normal': '0',
                'wide': '0.05em',
            },
            textColor: colors,
            backgroundColor: colors,
            borderWidth: {
                default: '1px',
                '0': '0',
                '2': '2px',
                '4': '4px',
                '8': '8px',
            },
            borderColor: Object.assign({ default: colors['grey-light'] }, colors),
            borderRadius: {
                'none': '0',
                'sm': '.125rem',
                default: '.25rem',
                'lg': '.5rem',
                'full': '9999px',
            },
            width: {
                'auto': 'auto',
                'px': '1px',
                '1': '0.25rem',
                '2': '0.5rem',
                '3': '0.75rem',
                '4': '1rem',
                '5': '1.25rem',
                '6': '1.5rem',
                '8': '2rem',
                '10': '2.5rem',
                '12': '3rem',
                '16': '4rem',
                '24': '6rem',
                '32': '8rem',
                '48': '12rem',
                '64': '16rem',
                '1/2': '50%',
                '1/3': '33.33333%',
                '2/3': '66.66667%',
                '1/4': '25%',
                '3/4': '75%',
                '1/5': '20%',
                '2/5': '40%',
                '3/5': '60%',
                '4/5': '80%',
                '1/6': '16.66667%',
                '5/6': '83.33333%',
                'full': '100%',
                'screen': '100vw'
            },
            height: {
                'auto': 'auto',
                'px': '1px',
                '1': '0.25rem',
                '2': '0.5rem',
                '3': '0.75rem',
                '4': '1rem',
                '5': '1.25rem',
                '6': '1.5rem',
                '8': '2rem',
                '10': '2.5rem',
                '12': '3rem',
                '16': '4rem',
                '20': '5rem',
                '24': '6rem',
                '32': '8rem',
                '48': '12rem',
                '64': '16rem',
                'full': '100%',
                'screen': '100vh'
            },
            minWidth: {
                '0': '0',
                'full': '100%',
            },
            minHeight: {
                '0': '0',
                'full': '100%',
                'screen': '100vh'
            },
            // maxWidth: {
            //     'xs': '20rem',
            //     'sm': '30rem',
            //     'md': '40rem',
            //     'lg': '44rem',
            //     'xl': '60rem',
            //     '2xl': '70rem',
            //     '3xl': '80rem',
            //     '4xl': '90rem',
            //     '5xl': '100rem',
            //     'full': '100%',
            // },
            maxHeight: {
                'full': '100%',
                'screen': '100vh',
            },
            padding: {
                'px': '1px',
                '0': '0',
                '1': '0.25rem',
                '2': '0.5rem',
                '3': '0.75rem',
                '4': '1rem',
                '5': '1.25rem',
                '6': '1.5rem',
                '8': '2rem',
                '10': '3rem',
                '12': '4rem',
                '14': '6rem',
                '16': '10rem',
            },
            margin: {
                'auto': 'auto',
                'px': '1px',
                '0': '0',
                '1': '0.25rem',
                '2': '0.5rem',
                '3': '0.75rem',
                '4': '1rem',
                '6': '1.5rem',
                '8': '2rem',
                '10': '4rem',
                '12': '4rem',
                '14': '6rem',
                '16': '10rem',
                '-px': '-1px',
                '-0': '-0',
                '-1': '-0.25rem',
                '-2': '-0.5rem',
                '-3': '-0.75rem',
                '-4': '-1rem',
                '-6': '-1.5rem',
                '-8': '-2rem',
                '-10': '-4rem',
                '-12': '-4rem',
                '-14': '-6rem',
                '-16': '-10rem',
            },
            zIndex: {
                'auto': 'auto',
                '0': 0,
                '10': 10,
                '20': 20,
                '30': 30,
                '40': 40,
                '50': 50,
            },
            opacity: {
                '0': '0',
                '25': '.25',
                '50': '.5',
                '75': '.75',
                '100': '1',
            },
            fill: {
                'current': 'currentColor',
            },
            stroke: {
                'current': 'currentColor',
            },
        },
    },
};
