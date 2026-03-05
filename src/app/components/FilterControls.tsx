import { LucideIcon } from "lucide-react";

interface FilterItem {
  type: string;
  icon: LucideIcon;
  count: number;
}

interface FilterControlsProps {
  title: string;
  filters: FilterItem[];
  activeFilter: string | null;
  onFilterChange: (filterType: string) => void;
  itemLabel?: string; // e.g., "itens", "perfis", "eventos"
}

export function FilterControls({
  title,
  filters,
  activeFilter,
  onFilterChange,
  itemLabel = "itens",
}: FilterControlsProps) {
  return (
    <section
      className="py-12 md:py-16"
      aria-label="Filtros de categoria"
    >
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 className="text-2xl md:text-3xl mb-8">{title}</h2>

        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          {filters.map((filter) => {
            const Icon = filter.icon;
            const isActive = activeFilter === filter.type;
            return (
              <button
                key={filter.type}
                onClick={() => onFilterChange(filter.type)}
                className={`bg-card border rounded-lg p-6 hover:border-primary hover:bg-accent transition-all focus:outline-none focus:ring-2 focus:ring-primary text-left ${
                  isActive
                    ? "border-primary bg-accent ring-2 ring-primary"
                    : "border-border"
                }`}
                aria-label={`Filtrar por ${filter.type} - ${filter.count} ${itemLabel}`}
                aria-pressed={isActive}
              >
                <Icon
                  className="w-8 h-8 text-primary mb-3"
                  aria-hidden="true"
                />
                <h3 className="text-lg mb-1">{filter.type}</h3>
                <p className="text-muted-foreground">
                  {filter.count} {itemLabel}
                </p>
              </button>
            );
          })}
        </div>
      </div>
    </section>
  );
}
