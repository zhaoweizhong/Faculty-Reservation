export default {
  namespaced: true,
  state: {
    isMobile: false,
    theme: 'dark',
    layout: 'side',
    systemName: '教师查询预约系统',
    copyright: '2018 Some rights reserved.\n This software is licensed under the MIT License.',
    footerLinks: [
      {link: 'https://github.com/zhaoweizhong/Faculty-Reservation', icon: 'github'}
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
