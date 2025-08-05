/**
 * @file
 * Font optimization JavaScript to prevent preload warnings.
 */

(function () {
  'use strict';

  // Force immediate use of preloaded fonts to prevent browser warnings
  function forceFont() {
    // Create invisible element to force font load
    const testElement = document.createElement('div');
    testElement.style.fontFamily = 'Lora, Georgia, serif';
    testElement.style.fontSize = '1px';
    testElement.style.opacity = '0';
    testElement.style.position = 'absolute';
    testElement.style.left = '-9999px';
    testElement.innerHTML = 'Font test';
    
    document.body.appendChild(testElement);
    
    // Force layout calculation
    testElement.offsetHeight;
    
    // Remove test element
    setTimeout(() => {
      document.body.removeChild(testElement);
    }, 100);
  }

  // Execute immediately when DOM is ready
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', forceFont);
  } else {
    forceFont();
  }

})();
