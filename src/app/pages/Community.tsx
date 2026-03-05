import {
  Briefcase,
  Building2,
  HandMetal,
  Eye,
  FileSpreadsheet,
  Waypoints,
  ArrowUpDown,
  Grid3x3,
} from "lucide-react";
import { useState, useEffect } from "react";
import { Link, useSearchParams } from "react-router";
import { ProfileCard } from "../components/ProfileCard";
import { Breadcrumb } from "../components/Breadcrumb";

type SortOption =
  | "name-az"
  | "name-za"
  | "location-az"
  | "location-za";
type ItemsPerPage = 8 | 16 | 24 | 32 | 40 | "all";

export function Community() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [activeFilter, setActiveFilter] = useState<string | null>(null);
  const [sortBy, setSortBy] = useState<SortOption>("name-az");
  const [itemsToShow, setItemsToShow] = useState<ItemsPerPage>(8);

  useEffect(() => {
    const filterFromUrl = searchParams.get("tipo");
    if (filterFromUrl) {
      setActiveFilter(filterFromUrl);
    }
  }, [searchParams]);

  const profileCategories = [
    {
      type: "Profissionais de tecnologia",
      icon: Briefcase,
      count: 156,
    },
    { type: "Empresas e ONGs", icon: Building2, count: 42 },
    {
      type: "Intérpretes de Libras",
      icon: HandMetal,
      count: 38,
    },
    { type: "Audiodescritores", icon: Eye, count: 27 },
    {
      type: "Tradutores de Braille",
      icon: FileSpreadsheet,
      count: 19,
    },
  ];

  const sampleProfiles = [
    {
      id: 1,
      name: "Ana Costa",
      type: "Profissionais de tecnologia",
      role: "UX Designer especialista em acessibilidade",
      location: "São Paulo, SP",
      description:
        "10+ anos de experiência criando interfaces acessíveis para produtos digitais de grande escala.",
      profileImage:
        "https://images.unsplash.com/photo-1649589244330-09ca58e4fa64?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcm9mZXNzaW9uYWwlMjB3b21hbiUyMHBvcnRyYWl0fGVufDF8fHx8MTc3MjU4MzU1N3ww&ixlib=rb-4.1.0&q=80&w=400",
      socialLinks: {
        linkedin: "https://linkedin.com/in/ana-costa",
        twitter: "https://twitter.com/anacosta",
        website: "https://anacosta.design",
      },
    },
    {
      id: 2,
      name: "Instituto Acessível",
      type: "Empresas e ONGs",
      role: "ONG dedicada à inclusão digital",
      location: "Rio de Janeiro, RJ",
      description:
        "Promovemos treinamentos e consultorias em acessibilidade digital para empresas e organizações públicas.",
      profileImage:
        "https://images.unsplash.com/photo-1674981208693-de5a9c4c4f44?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxidXNpbmVzcyUyMG9mZmljZSUyMGJ1aWxkaW5nfGVufDF8fHx8MTc3MjYwNzIxMXww&ixlib=rb-4.1.0&q=80&w=400",
      socialLinks: {
        linkedin:
          "https://linkedin.com/company/instituto-acessivel",
        instagram: "https://instagram.com/institutoacessivel",
        website: "https://institutoacessivel.org.br",
      },
    },
    {
      id: 3,
      name: "Carlos Mendes",
      type: "Intérpretes de Libras",
      role: "Intérprete certificado de Libras",
      location: "Brasília, DF",
      description:
        "Especialista em interpretação para eventos técnicos e corporativos, com foco em tecnologia e inovação.",
      profileImage:
        "https://images.unsplash.com/photo-1554765345-6ad6a5417cde?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcm9mZXNzaW9uYWwlMjBtYW4lMjBwb3J0cmFpdHxlbnwxfHx8fDE3NzI2MDE0NzV8MA&ixlib=rb-4.1.0&q=80&w=400",
      socialLinks: {
        linkedin: "https://linkedin.com/in/carlos-mendes",
        instagram: "https://instagram.com/carlosmendes_libras",
      },
    },
    {
      id: 4,
      name: "Beatriz Oliveira",
      type: "Profissionais de tecnologia",
      role: "Desenvolvedora Front-end",
      location: "Belo Horizonte, MG",
      description:
        "Desenvolvo aplicações web com foco em WCAG 2.1 AA e testes automatizados de acessibilidade.",
      profileImage:
        "https://images.unsplash.com/photo-1754298949882-216a1c92dbb5?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcm9mZXNzaW9uYWwlMjBidXNpbmVzc3dvbWFuJTIwcG9ydHJhaXR8ZW58MXx8fHwxNzcyNjY5Njk0fDA&ixlib=rb-4.1.0&q=80&w=400",
      socialLinks: {
        github: "https://github.com/beatrizoliveira",
        linkedin: "https://linkedin.com/in/beatriz-oliveira",
        twitter: "https://twitter.com/beatrizdev",
      },
    },
    {
      id: 5,
      name: "TechInclusiva",
      type: "Empresas e ONGs",
      role: "Startup de tecnologia assistiva",
      location: "Curitiba, PR",
      description:
        "Desenvolvemos soluções tecnológicas inovadoras para pessoas com deficiência.",
      profileImage:
        "https://images.unsplash.com/photo-1760138270903-d95903188730?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHx0ZWNoJTIwY29tcGFueSUyMGxvZ298ZW58MXx8fHwxNzcyNTk0Mjg1fDA&ixlib=rb-4.1.0&q=80&w=400",
      socialLinks: {
        linkedin: "https://linkedin.com/company/techinclusiva",
        instagram: "https://instagram.com/techinclusiva",
        facebook: "https://facebook.com/techinclusiva",
        website: "https://techinclusiva.com.br",
      },
    },
    {
      id: 6,
      name: "Fernanda Alves",
      type: "Audiodescritores",
      role: "Audiodescritor sênior",
      location: "Salvador, BA",
      description:
        "Especializada em audiodescrição para conteúdo educacional e cultural.",
      profileImage:
        "https://images.unsplash.com/photo-1689600944138-da3b150d9cb8?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxwcm9mZXNzaW9uYWwlMjBoZWFkc2hvdCUyMHdvbWFufGVufDF8fHx8MTc3MjU2Njg4OXww&ixlib=rb-4.1.0&q=80&w=400",
      socialLinks: {
        linkedin: "https://linkedin.com/in/fernanda-alves",
        website: "https://fernandaalves.com.br",
      },
    },
  ];

  const filteredProfiles = activeFilter
    ? sampleProfiles.filter((profile) => profile.type === activeFilter)
    : sampleProfiles;

  const sortedProfiles = [...filteredProfiles].sort((a, b) => {
    switch (sortBy) {
      case "name-az":
        return a.name.localeCompare(b.name);
      case "name-za":
        return b.name.localeCompare(a.name);
      case "location-az":
        return a.location.localeCompare(b.location);
      case "location-za":
        return b.location.localeCompare(a.location);
      default:
        return 0;
    }
  });

  const profilesToShow =
    itemsToShow === "all"
      ? sortedProfiles
      : sortedProfiles.slice(0, itemsToShow);

  const handleFilterClick = (type: string) => {
    if (activeFilter === type) {
      setActiveFilter(null);
      setSearchParams({});
    } else {
      setActiveFilter(type);
      setSearchParams({ tipo: type });
    }
  };

  return (
    <div className="flex-1">
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb items={[{ label: "Comunidade" }]} />
          <div className="flex items-center gap-4 mb-6">
            <Waypoints className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Comunidade</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Conheça profissionais e organizações que fazem a
            diferença em acessibilidade digital no Brasil.
          </p>
        </div>
      </section>

      <section
        className="py-12 md:py-16"
        aria-label="Categorias de perfis"
      >
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <h2 className="text-2xl md:text-3xl mb-8">
            Navegue por categoria
          </h2>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-16">
            {profileCategories.map((category) => {
              const Icon = category.icon;
              const isActive = activeFilter === category.type;
              return (
                <button
                  key={category.type}
                  onClick={() =>
                    handleFilterClick(category.type)
                  }
                  className={`bg-card border rounded-lg p-6 hover:border-primary hover:bg-accent transition-all focus:outline-none focus:ring-2 focus:ring-primary text-left ${
                    isActive
                      ? "border-primary bg-accent ring-2 ring-primary"
                      : "border-border"
                  }`}
                  aria-label={`Filtrar por ${category.type} - ${category.count} perfis`}
                  aria-pressed={isActive}
                >
                  <Icon
                    className="w-8 h-8 text-primary mb-3"
                    aria-hidden="true"
                  />
                  <h3 className="text-lg mb-1">
                    {category.type}
                  </h3>
                  <p className="text-muted-foreground">
                    {category.count} perfis
                  </p>
                </button>
              );
            })}
          </div>

          <div>
            <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
              <h2 className="text-2xl md:text-3xl">
                {activeFilter
                  ? `Perfis: ${activeFilter}`
                  : "Perfis"}
              </h2>

              <div className="flex flex-wrap gap-3 w-full sm:w-auto">
                <div className="flex-1 sm:flex-none min-w-[200px]">
                  <label
                    htmlFor="sort-select"
                    className="sr-only"
                  >
                    Ordenar perfis
                  </label>
                  <div className="relative">
                    <ArrowUpDown
                      className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none"
                      aria-hidden="true"
                    />
                    <select
                      id="sort-select"
                      value={sortBy}
                      onChange={(e) =>
                        setSortBy(e.target.value as SortOption)
                      }
                      className="w-full pl-10 pr-8 py-2 bg-card border border-border rounded-lg appearance-none cursor-pointer hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary transition-colors"
                    >
                      <option value="name-az">Nome A-Z</option>
                      <option value="name-za">Nome Z-A</option>
                      <option value="location-az">
                        Localização A-Z
                      </option>
                      <option value="location-za">
                        Localização Z-A
                      </option>
                    </select>
                    <div className="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                      <svg
                        width="12"
                        height="8"
                        viewBox="0 0 12 8"
                        fill="none"
                        aria-hidden="true"
                      >
                        <path
                          d="M1 1.5L6 6.5L11 1.5"
                          stroke="currentColor"
                          strokeWidth="1.5"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        />
                      </svg>
                    </div>
                  </div>
                </div>

                <div className="flex-1 sm:flex-none min-w-[200px]">
                  <label
                    htmlFor="items-select"
                    className="sr-only"
                  >
                    Perfis por página
                  </label>
                  <div className="relative">
                    <Grid3x3
                      className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground pointer-events-none"
                      aria-hidden="true"
                    />
                    <select
                      id="items-select"
                      value={itemsToShow}
                      onChange={(e) =>
                        setItemsToShow(
                          e.target.value === "all"
                            ? "all"
                            : (parseInt(
                                e.target.value,
                              ) as ItemsPerPage),
                        )
                      }
                      className="w-full pl-10 pr-8 py-2 bg-card border border-border rounded-lg appearance-none cursor-pointer hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary transition-colors"
                    >
                      <option value={8}>8 perfis</option>
                      <option value={16}>16 perfis</option>
                      <option value={24}>24 perfis</option>
                      <option value={32}>32 perfis</option>
                      <option value={40}>40 perfis</option>
                      <option value="all">
                        Todos ({filteredProfiles.length})
                      </option>
                    </select>
                    <div className="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                      <svg
                        width="12"
                        height="8"
                        viewBox="0 0 12 8"
                        fill="none"
                        aria-hidden="true"
                      >
                        <path
                          d="M1 1.5L6 6.5L11 1.5"
                          stroke="currentColor"
                          strokeWidth="1.5"
                          strokeLinecap="round"
                          strokeLinejoin="round"
                        />
                      </svg>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            {filteredProfiles.length > 0 ? (
              <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                {profilesToShow.map((profile) => (
                  <ProfileCard
                    key={profile.id}
                    name={profile.name}
                    type={profile.type}
                    role={profile.role}
                    location={profile.location}
                    description={profile.description}
                    profileImage={profile.profileImage}
                    socialLinks={profile.socialLinks}
                  />
                ))}
              </div>
            ) : (
              <div className="text-center py-12">
                <p className="text-muted-foreground text-lg">
                  Nenhum perfil encontrado para esta categoria.
                </p>
              </div>
            )}

            <div className="mt-12 text-center">
              <p className="text-muted-foreground text-lg mb-6">
                Faça parte da maior rede de profissionais de
                acessibilidade do Brasil.
              </p>
              <Link
                to="/submeter/perfil"
                className="inline-block bg-primary text-primary-foreground px-8 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
              >
                Submeta seu perfil
              </Link>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}

