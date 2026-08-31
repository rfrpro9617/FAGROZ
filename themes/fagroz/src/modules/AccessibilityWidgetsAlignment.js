/**
 * Accessibility Widgets Alignment Module
 * 
 * Controla o posicionamento de plugins externos (VLibras, UserWay)
 * para que fiquem alinhados no lado direito da tela
 */

export default class AccessibilityWidgetsAlignment {
  constructor() {
    this.config = {
      desktop: {
        vlibras: { right: '2rem', bottom: '3.5rem' },
        userway: { right: '2rem', bottom: '6rem' }
      },
      tablet: {
        vlibras: { right: '1rem', bottom: '9rem' },
        userway: { right: '1rem', bottom: '12rem' }
      },
      mobile: {
        vlibras: { right: '0.75rem', bottom: '9rem' },
        userway: { right: '0.75rem', bottom: '12rem' }
      }
    };

    this.DEBUG = true;
    this.init();
  }

  log(...args) {
    if (this.DEBUG) {
      console.log('[AccessibilityWidgets]', ...args);
    }
  }

  /**
   * Detecta o breakpoint atual
   */
  getCurrentBreakpoint() {
    const width = window.innerWidth;
    if (width <= 480) return 'mobile';
    if (width <= 768) return 'tablet';
    return 'desktop';
  }

  /**
   * Aplica o posicionamento aos elementos
   */
  applyPositioning() {
    const breakpoint = this.getCurrentBreakpoint();
    const positions = this.config[breakpoint];

    this.log(`Applying positioning for breakpoint: ${breakpoint} (${window.innerWidth}px)`);

    // VLibras
    const vlibras = document.getElementById('vLibras');
    if (vlibras) {
      this.log('Found VLibras, applying styles');
      vlibras.style.position = 'fixed';
      vlibras.style.right = positions.vlibras.right;
      vlibras.style.bottom = positions.vlibras.bottom;
      vlibras.style.zIndex = '9998';
    } else {
      this.log('VLibras not found');
    }

    // UserWay - múltiplos seletores
    const userWaySelectors = [
      '.uwy',
      '.userway_p2_container',
      '.userway-badge',
      '.userway_check_on'
    ];

    userWaySelectors.forEach(selector => {
      const elements = document.querySelectorAll(selector);
      if (elements.length > 0) {
        this.log(`Found ${elements.length} element(s) with selector: ${selector}`);
        elements.forEach((element, index) => {
          element.style.position = 'fixed';
          element.style.right = positions.userway.right;
          element.style.bottom = positions.userway.bottom;
          element.style.zIndex = '9999';
        });
      }
    });

    // UserWay iframe específico
    const userWayIframe = document.querySelector('iframe.userway_p2');
    if (userWayIframe) {
      this.log('Found UserWay iframe, applying styles');
      userWayIframe.style.position = 'fixed';
      userWayIframe.style.right = positions.userway.right;
      userWayIframe.style.bottom = positions.userway.bottom;
      userWayIframe.style.zIndex = '9999';
    }
  }

  /**
   * Monitora mudanças no DOM
   */
  initMutationObserver() {
    const observer = new MutationObserver((mutations) => {
      let shouldApply = false;
      let reason = '';

      mutations.forEach(mutation => {
        if (mutation.type === 'childList') {
          mutation.addedNodes.forEach(node => {
            if (node.nodeType === 1) {
              if (
                node.id === 'vLibras' ||
                node.classList.contains('uwy') ||
                node.classList.contains('userway_check_on') ||
                node.classList.contains('userway-badge') ||
                (node.tagName === 'IFRAME' && node.classList.contains('userway_p2'))
              ) {
                shouldApply = true;
                reason = `Detected: ${node.className || node.id}`;
              }

              if (node.querySelector && (
                node.querySelector('#vLibras') ||
                node.querySelector('.uwy') ||
                node.querySelector('.userway_p2_container')
              )) {
                shouldApply = true;
                reason = 'Detected child element';
              }
            }
          });
        }
      });

      if (shouldApply) {
        this.log('Mutation detected:', reason);
        requestAnimationFrame(() => this.applyPositioning());
      }
    });

    this.log('Starting MutationObserver');
    observer.observe(document.body, {
      childList: true,
      subtree: true
    });
  }

  /**
   * Listener para redimensionamento
   */
  initResizeListener() {
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        this.log('Window resized to', window.innerWidth, 'px');
        this.applyPositioning();
      }, 250);
    });
  }

  /**
   * Inicializa o módulo
   */
  init() {
    this.log('Initializing AccessibilityWidgetsAlignment');

    // Aplicar imediatamente
    this.applyPositioning();

    // Aplicar após delays (plugins podem ser lentos)
    setTimeout(() => {
      this.log('Applying positioning after 500ms');
      this.applyPositioning();
    }, 500);

    setTimeout(() => {
      this.log('Applying positioning after 2000ms');
      this.applyPositioning();
    }, 2000);

    // Iniciar observers
    this.initMutationObserver();
    this.initResizeListener();

    // Aplicar quando window load
    window.addEventListener('load', () => {
      this.log('Window loaded event, applying positioning');
      setTimeout(() => this.applyPositioning(), 500);
    });

    this.log('Initialization complete');
  }
}


