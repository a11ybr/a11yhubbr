import { ExternalLink, Linkedin, Globe, MapPin } from "lucide-react";
import { Tag } from "@/components/ui/Tag";

interface ProfileCardProps {
  name: string;
  role: string;
  bio: string;
  tags: string[];
  type: "profissional" | "empresa" | "interprete" | "audiodescritor" | "mentor";
  initials: string;
  location?: string;
  links?: { label: string; href: string; icon?: "linkedin" | "globe" }[];
}

const typeLabels: Record<ProfileCardProps["type"], string> = {
  profissional: "Profissional",
  empresa: "Empresa",
  interprete: "Intérprete de Libras",
  audiodescritor: "Audiodescritor",
  mentor: "Mentor(a)",
};

export function ProfileCard({
  name,
  role,
  bio,
  tags,
  type,
  initials,
  location,
  links = [],
}: ProfileCardProps) {
  return (
    <article className="card-base rounded-lg p-5 flex flex-col gap-4">
      {/* Header */}
      <div className="flex items-start gap-3">
        <div
          className="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm text-primary-foreground flex-shrink-0"
          style={{ background: "hsl(var(--primary))" }}
          aria-hidden
        >
          {initials}
        </div>
        <div className="min-w-0">
          <h3 className="text-base font-bold text-foreground leading-tight truncate">
            {name}
          </h3>
          <p className="text-sm text-muted-foreground">{role}</p>
          {location && <p className="text-xs text-muted-foreground mt-0.5 flex items-center gap-1"><MapPin size={11} className="text-primary flex-shrink-0" aria-hidden /> {location}</p>}
        </div>
      </div>

      {/* Type badge */}
      <Tag variant="muted">{typeLabels[type]}</Tag>

      {/* Bio */}
      <p className="text-sm text-muted-foreground leading-relaxed line-clamp-3">{bio}</p>

      {/* Tags */}
      {tags.length > 0 && (
        <div className="flex flex-wrap gap-1">
          {tags.slice(0, 4).map((tag) => (
            <Tag key={tag} variant="default" className="text-xs">
              {tag}
            </Tag>
          ))}
        </div>
      )}

      {/* Links */}
      {links.length > 0 && (
        <div className="flex items-center gap-2 pt-1 border-t border-border">
          {links.map((link, index) => {
            const Icon =
              link.icon === "linkedin"
                ? Linkedin
                : link.icon === "globe"
                  ? Globe
                  : ExternalLink;

            return (
              <a
                key={`${link.label}-${index}`}
                href={link.href}
                target="_blank"
                rel="noopener noreferrer"
                aria-label={`${link.label} — ${name}`}
                className="w-8 h-8 flex items-center justify-center rounded-md text-muted-foreground hover:text-primary hover:bg-primary-light transition-colors no-underline"
              >
                <Icon size={15} aria-hidden />
              </a>
            );
          })}
        </div>
      )}
    </article>
  );
}
