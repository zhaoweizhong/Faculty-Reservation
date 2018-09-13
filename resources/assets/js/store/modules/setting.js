export default {
  namespaced: true,
  state: {
    isMobile: false,
    theme: 'dark',
    layout: 'side',
    systemName: '教师查询预约系统',
    copyright: '2018 All rights reserved.',
    footerLinks: [
      {link: 'https://github.com/zhaoweizhong/Tutor-Reservation', icon: 'github'}
    ]
  },
  mutations: {
    setDevice (state, isMobile) {
      state.isMobile = isMobile
    },
    setTheme (state, theme) {
      state.theme = theme
    },
    setLayout (state, layout) {
      state.layout = layout
    }
  }
}
