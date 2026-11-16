import * as React from "react";
import { cn } from "@/lib/utils";

function Label({ className, ...props }: React.ComponentProps<"label">) {
  return <label className={cn("mb-1.5 block text-sm font-medium text-foreground", className)} {...props} />;
}

function FieldError({ children }: { children?: React.ReactNode }) {
  if (!children) return null;
  return <p className="mt-1 text-xs text-destructive">{children}</p>;
}

export { Label, FieldError };
