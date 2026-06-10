export const theme = {
  colors: {
    bg: '#0E1116',
    surface: '#171C24',
    surface2: '#1F2630',
    card: '#212A35',
    border: '#2C3744',
    primary: '#FF7A45',
    primaryDark: '#E5612C',
    accent: '#FFC14D',
    green: '#39D98A',
    red: '#FF5C5C',
    blue: '#4D9DFF',
    purple: '#A78BFA',
    text: '#F4F6F8',
    textDim: '#9AA7B5',
    textFaint: '#6B7889',
    white: '#FFFFFF',
  },
  radius: { sm: 10, md: 16, lg: 22, xl: 28 },
  space: (n: number) => n * 4,
  shadow: {
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 6 },
    shadowOpacity: 0.25,
    shadowRadius: 14,
    elevation: 8,
  },
};

export type Theme = typeof theme;
