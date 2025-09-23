// main.js - small client-side UX
document.addEventListener('DOMContentLoaded', function(){
  // toggle show password
  document.querySelectorAll('.show-pw').forEach(btn=>{
    btn.addEventListener('click', function(){
      const targetId = this.dataset.target;
      const input = document.getElementById(targetId);
      if (!input) return;
      if (input.type === 'password') {
        input.type = 'text';
        this.textContent = 'Hide';
      } else {
        input.type = 'password';
        this.textContent = 'Show';
      }
    });
  });

  // client side simple validation + animate on error
  document.querySelectorAll('form').forEach(form=>{
    form.addEventListener('submit', function(e){
      const invalid = Array.from(this.querySelectorAll('input[required]')).filter(i => !i.value.trim());
      if (invalid.length) {
        e.preventDefault();
        const existing = this.querySelector('.msg.error');
        if (!existing) {
          const msg = document.createElement('div');
          msg.className = 'msg error shake';
          msg.textContent = 'Please fill required fields.';
          this.prepend(msg);
          setTimeout(()=> msg.remove(), 2500);
        }
        invalid[0].focus();
      } else {
        // add subtle "loading" animation on submit button
        const btn = this.querySelector('button.btn');
        if (btn) {
          btn.disabled = true;
          btn.style.opacity = 0.8;
          btn.textContent = 'Processing...';
        }
      }
    });
  });
});
