import { Link } from "react-router-dom";
import { Linkedin, Instagram, Github, Twitter, Facebook, Heart } from "lucide-react";
const footerLinks = {
  plataforma: [
    { label: "Página inicial", href: "/" },
    { label: "Conteúdo", href: "/conteudo" },
    { label: "Recursos", href: "/recursos" },
    { label: "Comunidade", href: "/comunidade" },
  ],
  comunidade: [
    { label: "Eventos", href: "/eventos" },
    { label: "Newsletter", href: "/newsletter" },
    { label: "Submeter conteúdo", href: "/submeter" },
    { label: "Apoiar", href: "/apoiar" },
  ],
  sobre: [
    { label: "Sobre o projeto", href: "/sobre" },
    { label: "Código de conduta", href: "/sobre#conduta" },
    { label: "Política de privacidade", href: "/sobre#privacidade" },
    { label: "Acessibilidade", href: "/sobre#acessibilidade" },
  ],
};

export function Footer() {
  return (
    <footer
      className="border-t border-border bg-card mt-auto"
      role="contentinfo"
    >
      <div className="container-site py-12 lg:py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-8">
          {/* Brand */}
          <div className="lg:col-span-1">
            <Link to="/" className="inline-flex items-center gap-2 no-underline mb-4">
              <span
                className="flex items-center justify-center w-9 h-9 rounded-md font-extrabold text-sm text-primary-foreground"
                style={{ background: "hsl(var(--primary))" }}
              >
                a11y
              </span>
              <span className="font-extrabold text-xl tracking-tight text-foreground">
                BR
              </span>
            </Link>
            <p className="text-sm text-muted-foreground leading-relaxed mb-5">
              Hub colaborativo de acessibilidade digital em português. Feito pela comunidade, para a comunidade.
            </p>
            <div className="flex items-center gap-3">
              {[
                { icon: Github, label: "GitHub", href: "http://GitHub.com/a11yhubbr" },
                { icon: Twitter, label: "Twitter", href: "http://Twitter.com/a11yhubbr" },
                { icon: Linkedin, label: "LinkedIn", href: "http://LinkedIn.com/company/a11yhubbr" },
                { icon: Instagram, label: "Instagram", href: "http://Instagram.com/a11yhubbr" },
                { icon: Facebook, label: "Facebook", href: "http://Facebook.com/a11yhubbr" },
              ].map(({ icon: Icon, label, href }) => (
                <a
                  key={label}
                  href={href}
                  aria-label={label}
                  className="w-8 h-8 flex items-center justify-center rounded-md text-muted-foreground hover:text-primary hover:bg-primary-light transition-colors no-underline"
                >
                  <Icon size={16} role="img" aria-label={label} />
                </a>
              ))}
            </div>
          </div>

          {/* Links */}
          <div>
            <h2 className="text-xs font-bold uppercase tracking-widest text-foreground mb-4">
              Plataforma
            </h2>
            <ul className="flex flex-col gap-2 list-none p-0 m-0">
              {footerLinks.plataforma.map((l) => (
                <li key={l.href}>
                  <Link
                    to={l.href}
                    className="text-sm text-muted-foreground hover:text-primary no-underline transition-colors"
                  >
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h2 className="text-xs font-bold uppercase tracking-widest text-foreground mb-4">
              Comunidade
            </h2>
            <ul className="flex flex-col gap-2 list-none p-0 m-0">
              {footerLinks.comunidade.map((l) => (
                <li key={l.href}>
                  <Link
                    to={l.href}
                    className="text-sm text-muted-foreground hover:text-primary no-underline transition-colors"
                  >
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>

          <div>
            <h2 className="text-xs font-bold uppercase tracking-widest text-foreground mb-4">
              Sobre
            </h2>
            <ul className="flex flex-col gap-2 list-none p-0 m-0">
              {footerLinks.sobre.map((l) => (
                <li key={l.href}>
                  <Link
                    to={l.href}
                    className="text-sm text-muted-foreground hover:text-primary no-underline transition-colors"
                  >
                    {l.label}
                  </Link>
                </li>
              ))}
            </ul>
          </div>
        </div>

        {/* Bottom bar */}
        <div className="mt-10 pt-6 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-3">
          <p className="text-xs text-muted-foreground">
            © 2025 a11yBR. Conteúdo disponível sob licença{" "}
            <a href="#" className="text-primary hover:underline">
              Creative Commons CC BY 4.0
            </a>
          </p>
          <p className="text-xs text-muted-foreground flex items-center gap-1">
            Feito com <Heart size={11} className="text-primary" aria-label="amor" /> pela comunidade brasileira
          </p>
        </div>
      </div>
    </footer>
  );
}
