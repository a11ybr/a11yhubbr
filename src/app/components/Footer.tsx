import { Link } from "react-router";
import {
  Github,
  Twitter,
  Linkedin,
  Instagram,
  Facebook,
  Heart,
} from "lucide-react";

export function Footer() {
  const platformLinks = [
    { name: "Página inicial", path: "/", id: "platform-home" },
    {
      name: "Conteúdo",
      path: "/conteudos#conteudo",
      id: "platform-content",
    },
    {
      name: "Recursos",
      path: "/conteudos",
      id: "platform-resources",
    },
    {
      name: "Comunidade",
      path: "/comunidade",
      id: "platform-community",
    },
  ];

  const communityLinks = [
    {
      name: "Eventos",
      path: "/conteudos?tipo=Eventos",
      id: "community-events",
    },
    {
      name: "Newsletter",
      path: "/newsletter",
      id: "community-newsletter",
    },
    {
      name: "Submeter conteúdo",
      path: "/submeter",
      id: "community-submit",
    },
    { name: "Apoiar", path: "/sobre", id: "community-support" },
  ];

  const aboutLinks = [
    {
      name: "Sobre o projeto",
      path: "/sobre",
      id: "about-project",
    },
    {
      name: "Código de conduta",
      path: "/sobre#codigo-conduta",
      id: "about-conduct",
    },
    {
      name: "Política de privacidade",
      path: "/sobre",
      id: "about-privacy",
    },
    {
      name: "Acessibilidade",
      path: "/sobre#acessibilidade",
      id: "about-accessibility",
    },
  ];

  const socialLinks = [
    {
      name: "Github",
      icon: Github,
      url: "https://github.com/a11ybr/a11yhubbr",
    },
    {
      name: "Twitter",
      icon: Twitter,
      url: "https://twitter.com/a11yhubbr",
    },
    {
      name: "LinkedIn",
      icon: Linkedin,
      url: "https://linkedin.com/company/a11yhubbr",
    },
    {
      name: "Instagram",
      icon: Instagram,
      url: "https://instagram.com/a11yhubbr",
    },
    {
      name: "Facebook",
      icon: Facebook,
      url: "https://www.facebook.com/profile.php?id=61585078704922",
    },
  ];

  return (
    <footer
      className="bg-card border-t border-border mt-auto"
      role="contentinfo"
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 md:py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
          <div className="lg:col-span-1">
            <Link
              to="/"
              className="inline-flex items-center gap-2 text-2xl hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-primary rounded mb-4"
              aria-label="a11yBR - Página inicial"
            >
              <svg
                width="100%"
                height="56px"
                viewBox="0 0 973 156"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M365.436 4.88501C367.573 4.88509 369.56 5.94633 370.753 7.69663L370.982 8.05698L394.598 48.1529L418.45 8.03394L418.679 7.67777C419.873 5.93901 421.854 4.88541 423.981 4.88501H463.566C468.551 4.88547 471.642 10.3098 469.103 14.6L420.015 97.5531V143.859C420.015 147.413 417.133 150.295 413.579 150.295H371.74C368.186 150.295 365.305 147.413 365.304 143.859V97.0147L316.235 14.6167C313.68 10.3268 316.773 4.88568 321.766 4.88501H365.436Z"
                  fill="#0E285B"
                />
                <path
                  d="M305.698 4.88501C309.252 4.88558 312.134 7.76901 312.134 11.3232V143.859C312.134 147.413 309.252 150.294 305.698 150.295H263.859C260.305 150.295 257.424 147.413 257.423 143.859V48.2618H240.403C236.848 48.2618 233.965 45.3798 233.964 41.8257V11.3232C233.964 7.76866 236.848 4.88501 240.403 4.88501H305.698Z"
                  fill="#0E285B"
                />
                <path
                  d="M223.998 4.88501C227.553 4.88501 230.434 7.76867 230.434 11.3232V143.859C230.434 147.413 227.552 150.295 223.998 150.295H182.159C178.605 150.295 175.723 147.413 175.723 143.859V48.2618H158.702C155.148 48.2618 152.267 45.3798 152.266 41.8257V11.3232C152.266 7.76866 155.148 4.88501 158.702 4.88501H223.998Z"
                  fill="#0E285B"
                />
                <path
                  fillRule="evenodd"
                  clipRule="evenodd"
                  d="M106.921 4.88501C109.479 4.88501 111.795 6.40264 112.819 8.74627L170.757 141.282C172.615 145.534 169.5 150.295 164.859 150.295H121.918C119.256 150.294 116.867 148.655 115.911 146.17L108.331 126.449H62.1912L54.6111 146.17C53.6551 148.655 51.2654 150.295 48.6024 150.295H6.44282C1.80355 150.294 -1.31194 145.533 0.545135 141.282L58.4829 8.74627C59.5072 6.40298 61.8232 4.88545 64.3806 4.88501H106.921ZM78.2605 84.639H92.26L85.2603 66.4242L78.2605 84.639Z"
                  fill="#0E285B"
                />
                <path
                  fillRule="evenodd"
                  clipRule="evenodd"
                  d="M576.605 0C597.152 0 613.234 3.65601 624.003 11.7718C634.646 19.6515 640.22 30.0716 640.22 42.7542C640.22 51.0435 637.852 58.5788 633.119 65.2134L633.121 65.2156C630.893 68.3649 628.186 71.1546 625.043 73.6068C630.821 76.7775 635.601 80.8433 639.272 85.8593C644.551 92.7382 647.102 100.984 647.102 110.342C647.102 124.533 641.049 135.829 629.222 143.766L629.22 143.764C617.701 151.586 601.342 155.18 580.777 155.18H500.879C497.086 155.18 494.011 152.104 494.01 148.311V6.8708C494.01 3.07739 497.085 0 500.879 0H576.605ZM551.564 111.603H576.605C581.632 111.603 584.499 110.673 585.989 109.575L586.034 109.544L586.079 109.51C587.492 108.521 588.299 107.154 588.299 104.71C588.299 102.265 587.491 100.899 586.079 99.9095L585.989 99.8424C584.499 98.7448 581.633 97.8167 576.605 97.8167H551.564V111.603ZM551.564 56.1157H569.931C575.166 56.1156 577.992 55.2306 579.317 54.2912C580.705 53.2664 581.414 51.9674 581.415 49.8485C581.415 47.517 580.662 46.3241 579.445 45.4908L579.396 45.4595L579.349 45.4238C578.036 44.4763 575.206 43.5792 569.931 43.5792H551.564V56.1157Z"
                  fill="#3CA174"
                />
                <path
                  fillRule="evenodd"
                  clipRule="evenodd"
                  d="M729.051 0C752.034 0 770.297 5.0613 783.114 15.8947C795.945 26.7402 802.26 41.505 802.26 59.6528C802.259 71.6802 799.353 82.3238 793.365 91.3819L793.356 91.3953L793.345 91.411C789.061 97.7821 783.499 103.038 776.742 107.205L802.15 144.441C805.26 149 801.995 155.18 796.475 155.18H749.46C747.177 155.18 745.042 154.045 743.765 152.153L721.044 118.467H714.647V148.311C714.647 152.104 711.572 155.18 707.779 155.18H663.129C659.336 155.18 656.261 152.104 656.26 148.311V6.8708C656.26 3.07739 659.335 0 663.129 0H729.051ZM714.647 72.1781H728.009C734.261 72.1781 737.878 70.7588 739.863 68.8601L739.897 68.8243L739.933 68.793C742.164 66.7481 743.454 63.8877 743.454 59.6528C743.454 55.4172 742.163 52.5555 739.933 50.5104L739.897 50.4791L739.863 50.4455C737.878 48.5465 734.262 47.1253 728.009 47.1253H714.647V72.1781Z"
                  fill="#3CA174"
                />
                <path
                  d="M827.356 8.885C827.356 6.67586 829.147 4.88501 831.356 4.88501H968.766C970.975 4.88501 972.766 6.67587 972.766 8.88501V146.295C972.766 148.504 970.975 150.295 968.766 150.295H831.356C829.147 150.295 827.356 148.504 827.356 146.295V8.885Z"
                  fill="#3CA174"
                />
                <path
                  d="M897.204 7.79973C898.773 6.19954 901.35 6.19954 902.918 7.79973L936.048 41.6029L969.851 74.7333C971.452 76.3016 971.452 78.8784 969.851 80.4467L936.048 113.577L902.918 147.38C901.35 148.98 898.773 148.98 897.204 147.38L864.074 113.577L830.271 80.4467C828.671 78.8784 828.671 76.3016 830.271 74.7333L864.074 41.6029L897.204 7.79973Z"
                  fill="#FAD47A"
                />
                <path
                  d="M940.776 77.59C940.776 100.076 922.547 118.305 900.061 118.305C877.575 118.305 859.346 100.076 859.346 77.59C859.346 55.1038 877.575 36.8752 900.061 36.8752C922.547 36.8752 940.776 55.1038 940.776 77.59Z"
                  fill="#7ABEFA"
                />
              </svg>
            </Link>
            <p className="text-muted-foreground text-sm leading-relaxed mb-6">
              Hub colaborativo de acessibilidade digital em
              português. Feito pela comunidade, para a
              comunidade.
            </p>

            <div className="flex items-center gap-4">
              {socialLinks.map((social) => {
                const Icon = social.icon;
                return (
                  <a
                    key={social.name}
                    href={social.url}
                    target="_blank"
                    rel="noopener noreferrer"
                    className="text-muted-foreground hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary rounded"
                    aria-label={social.name}
                  >
                    <Icon
                      className="w-5 h-5"
                      aria-hidden="true"
                    />
                  </a>
                );
              })}
            </div>
          </div>

          <div>
            <h2 className="text-sm uppercase tracking-wider text-primary mb-4 font-bold">
              Plataforma
            </h2>
            <nav aria-label="Links da plataforma">
              <ul className="space-y-3">
                {platformLinks.map((link) => (
                  <li key={link.id}>
                    <Link
                      to={link.path}
                      className="text-foreground hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary rounded text-sm"
                    >
                      {link.name}
                    </Link>
                  </li>
                ))}
              </ul>
            </nav>
          </div>

          <div>
            <h2 className="text-sm uppercase tracking-wider text-primary mb-4 font-bold">
              Comunidade
            </h2>
            <nav aria-label="Links da comunidade">
              <ul className="space-y-3">
                {communityLinks.map((link) => (
                  <li key={link.id}>
                    <Link
                      to={link.path}
                      className="text-foreground hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary rounded text-sm"
                    >
                      {link.name}
                    </Link>
                  </li>
                ))}
              </ul>
            </nav>
          </div>

          <div>
            <h2 className="text-sm uppercase tracking-wider text-primary mb-4 font-bold">
              Sobre
            </h2>
            <nav aria-label="Links sobre o projeto">
              <ul className="space-y-3">
                {aboutLinks.map((link) => (
                  <li key={link.id}>
                    <Link
                      to={link.path}
                      className="text-foreground hover:text-primary transition-colors focus:outline-none focus:ring-2 focus:ring-primary rounded text-sm"
                    >
                      {link.name}
                    </Link>
                  </li>
                ))}
              </ul>
            </nav>
          </div>
        </div>

        <div className="border-t border-border mt-12 pt-8 flex flex-col sm:flex-row justify-between items-center gap-4">
          <p className="text-sm text-muted-foreground">
            © {new Date().getFullYear()} <span className="font-semibold">A11yBR</span>. Conteúdo
            disponível sob licença{" "}
            <a
              href="https://creativecommons.org/licenses/by/4.0/"
              target="_blank"
              rel="noopener noreferrer"
              className="hover:text-primary focus:outline-none focus:ring-2 focus:ring-primary rounded"
            >
              Creative Commons CC BY 4.0
            </a>
          </p>
          <p className="text-sm text-muted-foreground">
            Feito com{" "}
            <Heart
              className="w-4 h-4 text-red-500 inline-block"
              aria-label="amor"
            />
            {" "}
            pela comunidade brasileira
          </p>
        </div>
      </div>
    </footer>
  );
}

