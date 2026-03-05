import { ChevronRight } from "lucide-react";
import { Link } from "react-router";

interface BreadcrumbItem {
  label: string;
  href?: string;
}

interface BreadcrumbProps {
  items: BreadcrumbItem[];
}

export function Breadcrumb({ items }: BreadcrumbProps) {
  return (
    <nav aria-label="Breadcrumb" className="mb-6">
      <ol className="flex items-center text-sm gap-2 flex-wrap">
        <li>
          <Link
            to="/"
            className="text-primary-foreground/80 hover:text-primary-foreground focus:outline-none focus:ring-2 focus:ring-primary-foreground/50 rounded inline-flex items-center gap-1 transition-colors"
            aria-label="Ir para página inicial"
          >
            <span>Página inicial</span>
          </Link>
        </li>

        {items.map((item, index) => {
          const isLast = index === items.length - 1;
          const key = `${item.href ?? "current"}-${item.label}`;

          return (
            <li key={key} className="flex items-center gap-2">
              <ChevronRight
                className="w-4 h-4 text-primary-foreground/60"
                aria-hidden="true"
              />
              {item.href && !isLast ? (
                <Link
                  to={item.href}
                  className="text-primary-foreground/80 hover:text-primary-foreground focus:outline-none focus:ring-2 focus:ring-primary-foreground/50 rounded transition-colors"
                >
                  {item.label}
                </Link>
              ) : (
                <span
                  className="text-primary-foreground font-semibold"
                  aria-current={isLast ? "page" : undefined}
                >
                  {item.label}
                </span>
              )}
            </li>
          );
        })}
      </ol>
    </nav>
  );
}

